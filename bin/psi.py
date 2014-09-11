__author__ = 'zhangbin'
import MySQLdb
import MySQLdb.cursors
import MySQLdb.converters
from MySQLdb.constants import FIELD_TYPE
import json
import time
import datetime
import calendar
import math
import sys


class Psi:
    def __init__(self):
        conv = MySQLdb.converters.conversions.copy()
        conv[FIELD_TYPE.DATE] = str
        conv[FIELD_TYPE.LONGLONG] = str
        conv[FIELD_TYPE.LONG] = str
        conv[FIELD_TYPE.NEWDECIMAL] = str

        self.conn = MySQLdb.connect(host='localhost', user='root', passwd='64297881', db='lenovo', conv=conv)
        self.cursor = self.conn.cursor(MySQLdb.cursors.DictCursor)

        sql = 'select * from BasicData'
        self.cursor.execute(sql)
        self.__basic_data = {}
        for row in self.cursor:
            self.__basic_data[row['name']] = row['value']

        sql = 'select * from ScRoute where deleted = 0 and poType = 1'
        self.cursor.execute(sql)
        self.__basic_data['lt'] = {}
        for row in self.cursor.fetchall():
            self.__basic_data['lt'][str(row['country'])] = row['totalDays']

        self.__basic_data['saftyStock'] = json.loads(self.__basic_data['saftyStock'])

        sql = 'select * from Country where forPSI = 1 order by country'
        self.cursor.execute(sql)
        self.__countries = self.cursor.fetchall()

        now = time.localtime()
        self.__months = dict()
        self.__months['lastMonth'] = datetime.datetime.fromtimestamp(time.mktime([now.tm_year, now.tm_mon - 1, 1, 0, 0, 0, 0, 0, 0])).strftime('%Y-%m')
        self.__months['last2Month'] = datetime.datetime.fromtimestamp(time.mktime([now.tm_year, now.tm_mon - 2, 1, 0, 0, 0, 0, 0, 0])).strftime('%Y-%m')
        self.__months['last3Month'] = datetime.datetime.fromtimestamp(time.mktime([now.tm_year, now.tm_mon - 3, 1, 0, 0, 0, 0, 0, 0])).strftime('%Y-%m')
        self.__months['thisMonth'] = time.strftime('%Y-%m')

        self.__failure_rate = dict()
        self.__model_warranty = dict()
        self.__applies = {}
        self.pn = ''

        sql = 'select * from AbcClass where month = %s and vendorId = 0'
        self.cursor.execute(sql, [self.__months['thisMonth']])
        self.__abcClass = dict()
        for row in self.cursor:
            self.__abcClass[row['pn']] = row['abcClass']

        sql = 'select id,pn,npiLog,EOL,en,partsGroup,partsGroupId from PartsMaitrox'
        self.cursor.execute(sql)
        self.__parts = dict()
        for row in self.cursor:
            self.__parts[row['pn']] = row

        sql = 'select pn,beginTime,endTime from Warning where vendorId = 0'
        self.cursor.execute(sql)
        self.__warnings = dict()
        for row in self.cursor:
            self.__warnings[row['pn']] = row

        self.__usages = dict()
        sql = 'select pn,country,sum(qty) as qty from PartsUseNumber where month = %s group by country,pn'
        for month in ['lastMonth', 'last2Month', 'last3Month']:
            self.__usages[month] = dict()
            self.cursor.execute(sql, [self.__months[month]])
            for row in self.cursor:
                key = "_".join([row['pn'], row['country']])
                self.__usages[month][key] = int(row['qty'])

        self.__parts_shipping = dict()
        sql = 'select pn,country,sum(qty) as qty from PartsShipping group by pn,country'
        self.cursor.execute(sql)
        for row in self.cursor:
            if row['country'] not in self.__parts_shipping.keys():
                self.__parts_shipping[row['country']] = dict()
            self.__parts_shipping[row['country']][row['pn']] = int(row['qty'])

        self.__parts_inventory = dict()
        sql = 'select sum(PartsInventory.qty) as qty,pn,PartsInventory.country from PartsInventory ' \
              'inner join Warehouse on PartsInventory.warehouseId = Warehouse.id where month = %s ' \
              'and Warehouse.goodOrBad = 0 group by pn,PartsInventory.country'
        self.cursor.execute(sql, [self.__months['thisMonth']])
        for row in self.cursor:
            if row['country'] not in self.__parts_inventory.keys():
                self.__parts_inventory[row['country']] = dict()
            self.__parts_inventory[row['country']][row['pn']] = int(row['qty'])

    def generate(self):
        pns = self.__pn()
        now = time.time()

        data = list()
        data.append(self.__get_header_1())
        data.append(self.__get_header_2())
        data.append(self.__get_header_3())

        for pn, slave in pns.items():
            self.pn = pn
            models = self.__models(pn)
            # if not models:
            #     continue

            planning = []
            begin_time = []
            end_time = []
            slave_parts = []

            pn_tmp = [pn]
            part = self.__parts.get(pn, {})

            if pn in self.__warnings.keys():
                row = self.__warnings[pn]
                if row['beginTime']:
                    begin_time.append(row['beginTime'])
                if row['endTime']:
                    end_time.append(row['endTime'])

            is_slave_dict = isinstance(slave, dict)
            if is_slave_dict and 'slave' in slave.keys():
                for slave_pn in slave['slave']:
                    if slave_pn in self.__warnings.keys():
                        row = self.__warnings[slave_pn]
                        if row['beginTime']:
                            begin_time.append(row['beginTime'])
                        if row['endTime']:
                            end_time.append(row['endTime'])
                    pn_tmp.append(slave_pn)
                    slave_parts.append(self.__parts[slave_pn])

            if part:
                npi_flag = False
                is_part_dict = isinstance(part, dict)
                if is_part_dict and part['npiLog']:
                    npi_flag = True
                else:
                    for row in slave_parts:
                        if row['npiLog']:
                            npi_flag = True
                            break

                eol_flag = True
                if is_part_dict and part['EOL']:
                    for row in slave_parts:
                        if row['EOL']:
                            continue
                        eol_flag = False
                        break
                else:
                    eol_flag = False

            planning.append(pn)
            planning.append(self.__abcClass[pn] if pn in self.__abcClass.keys() else '')
            planning.append(part['en'] if part else '')
            planning.append(slave['group'] if is_slave_dict else '')
            planning.append(part['partsGroup'] if part else '')
            if part:
                if models:
                    planning.append(",".join(models))
                    min_begin_time = min(begin_time) if begin_time else 0
                    max_end_time = max(end_time) if end_time else 0
                    remain = 0
                    if max_end_time:
                        remain = round((time.mktime(datetime.datetime.strptime(max_end_time, '%Y-%m-%d').timetuple()) - now) / 86400 / 30, 1)
                    planning.append(remain if remain else '')
                    planning.append(min_begin_time)
                    planning.append(max_end_time)
                else:
                    planning.append('')
                    planning.append('')
                    planning.append('')
                    planning.append('')
                planning.append('Y' if npi_flag else 'N')
                planning.append('Y' if eol_flag else 'N')
            else:
                planning.append('')
                planning.append('')
                planning.append('')
                planning.append('')
                planning.append('')
                planning.append('')

            category_id = part['partsGroupId'] if part else ''
            qty_by_weight, qty_by_ra = self.__get_demand(planning, pn_tmp, models, category_id, npi_flag)
            demands = {}
            for country in self.__countries:
                country_name = country['country']
                demands[country_name] = dict()
                demands[country_name]['f'] = qty_by_ra[country_name]
                demands[country_name]['w'] = qty_by_weight[country_name]

            forecast_inventories = self.__get_forecast_inventory(planning, demands)
            inventories = self.__merge_inventory(planning, pn_tmp)
            shippings = self.__merge_shipping(planning, pn_tmp)
            applies = self.__merge_parts_apply(planning, pn_tmp)
            usages = {}
            for country in self.__countries:
                country_name = country['country']
                usages[country_name] = inventories[country_name] + shippings[country_name] - applies[country_name]

            self.__get_inventory_discrepancy(planning, forecast_inventories, usages)
            self.__get_to(planning, usages, demands)
            qty = 0
            sql = 'select sum(qty) from Inventory where warehouseId = %s and pn = %s'
            for row in pn_tmp:
                self.cursor.execute(sql, [509, row])
                qty += int(self.cursor.fetchone().values()[0] or 0)
            planning.append(qty)

            qty = 0
            sql = 'select sum(ShippingDetails.qty) from ShippingDetails inner join ShippingOrder ' \
                  'on ShippingDetails.shippingOrderId = ShippingOrder.id ' \
                  'where partsPN = %s and destinationWarehouseId = %s and status = %s'
            for row in pn_tmp:
                self.cursor.execute(sql, [row, 509, 1])
                qty += int(self.cursor.fetchone().values()[0] or 0)
            planning.append(qty)
            data.append(planning)

        return data

    def reset(self, filename):
        data = self.generate()
        import base64
        import zlib
        data = base64.encodestring(zlib.compress(json.dumps(data)))
        fp = open(filename, 'w')
        fp.write(data)
        fp.close()


    def __pn(self):
        sql = 'select newPN1,newPN2,newPN3 from ServiceOrder where (newPN1 != "" or newPN2 != "" or newPN3 != "") and deleted = 0';
        self.cursor.execute(sql)
        pns = {}
        for row in self.cursor:
            if row['newPN1']:
                pns[row['newPN1']] = row['newPN1']
            if row['newPN2']:
                pns[row['newPN2']] = row['newPN2']
            if row['newPN3']:
                pns[row['newPN3']] = row['newPN3']

        sql = 'select distinct pn from Inventory'
        self.cursor.execute(sql)
        for inventory in self.cursor:
            pns[inventory['pn']] = inventory['pn']

        sql = 'select * from PartsCluster'
        self.cursor.execute(sql)
        for cluster in self.cursor:
            slaves = json.loads(cluster['slavePn'])
            pns[cluster['masterPn']] = {"slave": slaves, "group": cluster['cluster']}
            for slave in slaves:
                if slave in pns.keys():
                    del pns[slave]
        return pns

    def __models(self, pn):
        sql = 'select distinct modelId from PhoneBom where pn = %s'
        self.cursor.execute(sql, [pn])
        models = ','.join([str(bom['modelId']) for bom in self.cursor if bom['modelId']])
        if models:
            sql = 'select distinct modeltype from Model where id in ('+models+')'
            self.cursor.execute(sql)
            return [model['modeltype'] for model in self.cursor]

    def __get_demand(self, planning, pns, models, category_id, npi_flag):
        qty_by_weight = {}
        qty_by_ra = {}

        for country in self.__countries:
            demand_by_mffr = 0
            if models:
                for model in models:
                    rate = self.__get_failure_rate(model, category_id, country['country'], self.__months['lastMonth'])
                    warranty = self.__get_model_warranty(model, country['country'], self.__months['thisMonth'])
                    demand_by_mffr += math.ceil(warranty * rate / 100)
            planning.append(demand_by_mffr)
            qty_by_ra[country['country']] = demand_by_mffr

            last_3_month = 0
            last_2_month = 0
            last_month = 0
            for pn in pns:
                key = "_".join([pn, country['country']])
                last_3_month += self.__usages['last3Month'][key] if key in self.__usages['last3Month'] else 0
                last_2_month += self.__usages['last2Month'][key] if key in self.__usages['last2Month'] else 0
                last_month += self.__usages['lastMonth'][key] if key in self.__usages['lastMonth'] else 0

            demand_by_weight = self.__get_qty_by_weight(last_3_month, last_2_month, last_month)
            planning.append(demand_by_weight)
            qty_by_weight[country['country']] = demand_by_weight
        return qty_by_weight, qty_by_ra

    def __get_failure_rate(self, model, category_id, country, month):
        if category_id is None:
            category_id = ''

        if not self.__failure_rate:
            sql = 'select * from FailureRateModel where month = %s'
            self.cursor.execute(sql, [month])
            for row in self.cursor:
                key = "_".join([row['model'], row['categoryId'], row['country']])
                self.__failure_rate[key] = float(row['rate'])
        key = "_".join([model, category_id, country])
        return self.__failure_rate[key] if key in self.__failure_rate.keys() else 0

    def __get_model_warranty(self, model, country, date):
        if not self.__model_warranty:
            sql = 'select model,country,sum(number) as number from ModelWarranty where expireTime > %s and salesTime < %s group by model,country'
            year, month = date.split("-")

            day = calendar.monthrange(int(year), int(month))[1]
            month_end = "-".join([str(year), str(month), str(day)])
            self.cursor.execute(sql, [month_end, month_end])
            for row in self.cursor:
                key = "_".join([row['model'], row['country']])
                self.__model_warranty[key] = int(row['number'])

        key = "_".join([model, country])
        return self.__model_warranty[key] if key in self.__model_warranty.keys() else 0

    def __get_qty_by_weight(self, first_month, second_month, third_month):
        qty = first_month * (float(self.__basic_data['weight1']) / 100) \
              + second_month * (float(self.__basic_data['weight2']) / 100) \
              + third_month * (float(self.__basic_data['weight3']) / 100)
        return math.ceil(qty)

    def __get_forecast_inventory(self, planning, demands):
        sorts = ['f', 'w']
        inventory = {}

        for country in self.__countries:
            country_name = str(country['country'])
            inventory[country_name] = dict()
            lt = round(float(self.__basic_data['lt'][country_name]) / 30, 1)
            safty_stock = self.__basic_data['saftyStock'][country_name] if country_name in self.__basic_data['saftyStock'].keys() else 0
            safty_stock = int(safty_stock)
            for row in sorts:
                demand = int(demands[country_name][row])
                if demand:
                    inventory[country_name][row] = math.ceil(demand * (1 + lt + safty_stock))
                else:
                    inventory[country_name][row] = 0
                planning.append(inventory[country_name][row])
        return inventory

    def __merge_inventory(self, planning, pns):
        inventories = dict()
        for country in self.__countries:
            qty = 0
            for pn in pns:
                if country['country'] in self.__parts_inventory.keys() and pn in self.__parts_inventory[country['country']].keys():
                    qty += self.__parts_inventory[country['country']][pn]
            planning.append(qty)
            inventories[country['country']] = qty
        return inventories

    def __merge_shipping(self, planning, pns):
        shippings = dict()
        for country in self.__countries:
            qty = 0
            for pn in pns:
                if country['country'] in self.__parts_shipping.keys() and pn in self.__parts_shipping[country['country']].keys():
                    qty += self.__parts_shipping[country['country']][pn]
            planning.append(qty)
            shippings[country['country']] = qty
        return shippings

    def __merge_parts_apply(self, planning, pns):
        if not self.__applies:
            for country in self.__countries:
                sql = 'select id from Vendor where country = %s'
                self.cursor.execute(sql, [country['country']])
                vendors = []
                for row in self.cursor:
                    vendors.append(row['id'])
                vendor = ",".join(vendors)
                sql = 'select newPN1,newPN2,newPN3 from ServiceOrder where deleted = 0 and vendorId in (' + vendor +') and status in (%s,%s,%s)'
                self.__applies[country['country']] = {}
                self.cursor.execute(sql, [2, 9, 10])
                for row in self.cursor:
                    if row['newPN1']:
                        if row['newPN1'] not in self.__applies[country['country']].keys():
                            self.__applies[country['country']][row['newPN1']] = 0
                        self.__applies[country['country']][row['newPN1']] += 1
                    if row['newPN2']:
                        if row['newPN2'] not in self.__applies[country['country']].keys():
                            self.__applies[country['country']][row['newPN2']] = 0
                        self.__applies[country['country']][row['newPN2']] += 1
                    if row['newPN3']:
                        if row['newPN3'] not in self.__applies[country['country']].keys():
                            self.__applies[country['country']][row['newPN3']] = 0
                        self.__applies[country['country']][row['newPN3']] += 1

        applies = {}
        for country in self.__countries:
            qty = 0
            for pn in pns:
                if pn in self.__applies[country['country']].keys():
                    qty += self.__applies[country['country']][pn]
            applies[country['country']] = qty
            planning.append(qty)
        return applies

    def __get_inventory_discrepancy(self, planning, forecast_inventories, usages):
        sorts = ['f', 'w']
        for country in self.__countries:
            for row in sorts:
                discrepancy = forecast_inventories[country['country']][row] - usages[country['country']]
                planning.append(discrepancy)

    def __get_to(self, planning, usages, demands):
        sorts = ['f', 'w']
        for country in self.__countries:
            usage = int(usages[country['country']])
            demand = demands[country['country']]
            for row in sorts:
                d = int(demand[row])
                if d == 0 and usage == 0:
                    to = 'N/A'
                elif d == 0 and usage != 0:
                    to = '9999'
                elif usage == 0:
                    to = '0'
                else:
                    to = round(float(usage) / float(d), 2)
                planning.append(to)

    def __get_header_1(self):
        header = []
        for i in range(1, 12):
            header.append('')
        country_cnt = len(self.__countries)
        cnt = country_cnt * 2

        header.append('FCST Next Month Demand')
        for i in range(1, cnt):
            header.append('')

        header.append('FCST Inventory')
        for i in range(1, cnt):
            header.append('')

        header.append('Actual Inventory')
        for i in range(1, country_cnt):
            header.append('')

        header.append('Shipping Order Qty.')
        for i in range(1, country_cnt):
            header.append('')

        header.append('Parts Apply')
        for i in range(1, country_cnt):
            header.append('')

        header.append('Inventory Discrepancy')
        for i in range(1, cnt):
            header.append('')

        header.append('TO')
        for i in range(1, cnt):
            header.append('')

        return header

    def __get_header_2(self):
        header = []
        for i in range(1, 12):
            header.append('')

        #FCST Next Month Demand
        for country in self.__countries:
            header.append(country['code'])
            header.append('')

        #FCST Inventory
        for country in self.__countries:
            header.append(country['code'])
            header.append('')

        #Actual Inventory
        for country in self.__countries:
            header.append(country['code'])

        #Shipping Order Qty.
        for country in self.__countries:
            header.append(country['code'])

        #Parts Apply
        for country in self.__countries:
            header.append(country['code'])

        #Inventory Discrepancy
        for country in self.__countries:
            header.append(country['code'])
            header.append('')

        #TO
        for country in self.__countries:
            header.append(country['code'])
            header.append('')

        header.append('Maitrox HK Good Inventory')
        header.append('Maitrox Shipping Order')
        header.append('Purchase On Order')
        return header

    def __get_header_3(self):
        header = ['PN', 'ABC Class', 'Parts Description', 'Group', 'Category', 'Model', 'Remain Warranty Month(MODIFY)',
                  'Begin Warranty Time', 'End Warranty Time', 'NPI Flag', 'EOL Flag']
        country_cnt = len(self.__countries)
        #FCST Next Month Demand
        for i in range(0, country_cnt):
            header.append('F')
            header.append('W')

        #FCST Inventory
        for i in range(0, country_cnt):
            header.append('F')
            header.append('W')

        #Actual Inventory
        for i in range(0, country_cnt):
            header.append('')

        #Shipping Order Qty.
        for i in range(0, country_cnt):
            header.append('')

        #Parts Apply
        for i in range(0, country_cnt):
            header.append('')

        #Inventory Discrepancy
        for i in range(0, country_cnt):
            header.append('F')
            header.append('W')

        #TO
        for i in range(0, country_cnt):
            header.append('F')
            header.append('W')
        return header

psi = Psi()
filename = sys.argv[1]
psi.reset(filename)