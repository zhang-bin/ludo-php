# coding: utf-8
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


class Replenish:
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
            self.__basic_data['lt'][str(row['vendorId'])] = row['totalDays']

        self.__basic_data['saftyStock'] = json.loads(self.__basic_data['saftyStock'])
        sql = 'select Vendor.id,Country.country,name,countryShortName from Vendor inner join Country ' \
              'on Vendor.country = Country.country where Country.forPSI = 1 order by Country.country'
        self.cursor.execute(sql)
        self.__vendors = self.cursor.fetchall()

        self.__countries = dict()
        for row in self.cursor:
            self.__countries[row['id']] = row['country']

        now = time.localtime()
        self.__months = dict()
        self.__months['lastMonth'] = datetime.datetime.fromtimestamp(time.mktime([now.tm_year, now.tm_mon - 1, 1, 0, 0, 0, 0, 0, 0])).strftime('%Y-%m')
        self.__months['last2Month'] = datetime.datetime.fromtimestamp(time.mktime([now.tm_year, now.tm_mon - 2, 1, 0, 0, 0, 0, 0, 0])).strftime('%Y-%m')
        self.__months['last3Month'] = datetime.datetime.fromtimestamp(time.mktime([now.tm_year, now.tm_mon - 3, 1, 0, 0, 0, 0, 0, 0])).strftime('%Y-%m')
        self.__months['thisMonth'] = time.strftime('%Y-%m')

        self.__failure_rate = dict()
        self.__model_warranty = dict()
        self.__model_warranty_setting = dict()
        self.__applies = {}
        self.pn = ''

        sql = 'select * from ModelWarrantySetting'
        self.cursor.execute(sql)
        for row in self.cursor:
            self.__model_warranty_setting[row['vendorId']] = row['percentage']
        for vendor in self.__vendors:
            if vendor['id'] not in self.__model_warranty_setting.keys():
                self.__model_warranty_setting[vendor['id']] = 100

        sql = 'select * from AbcClass where month = %s and vendorId = 0'
        self.cursor.execute(sql, [self.__months['thisMonth']])
        self.__abcClass = dict()
        for row in self.cursor:
            self.__abcClass[row['pn']] = row['abcClass']

        sql = 'select id,pn,EOL,en,partsGroup,partsGroupId from PartsMaitrox'
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
        sql = 'select pn,vendorId,sum(qty) as qty from PartsUseNumber where month = %s group by vendorId,pn'
        for month in ['lastMonth', 'last2Month', 'last3Month']:
            self.__usages[month] = dict()
            self.cursor.execute(sql, [self.__months[month]])
            for row in self.cursor:
                key = "_".join([row['pn'], row['vendorId']])
                self.__usages[month][key] = int(row['qty'])

        self.__parts_shipping = dict()
        sql = 'select pn,vendorId,sum(qty) as qty from PartsShipping group by pn,vendorId'
        self.cursor.execute(sql)
        for row in self.cursor:
            if row['vendorId'] not in self.__parts_shipping.keys():
                self.__parts_shipping[row['vendorId']] = dict()
            self.__parts_shipping[row['vendorId']][row['pn']] = int(row['qty'])

        self.__parts_inventory = dict()
        sql = 'select sum(PartsInventory.qty) as qty,pn,PartsInventory.vendorId from PartsInventory ' \
              'inner join Warehouse on PartsInventory.warehouseId = Warehouse.id where month = %s ' \
              'and Warehouse.goodOrBad = 0 group by pn,PartsInventory.vendorId'
        self.cursor.execute(sql, [self.__months['thisMonth']])
        for row in self.cursor:
            if row['vendorId'] not in self.__parts_inventory.keys():
                self.__parts_inventory[row['vendorId']] = dict()
            self.__parts_inventory[row['vendorId']][row['pn']] = int(row['qty'])

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
            # if is_slave_dict and 'slave' in slave.keys():
            #     for slave_pn in slave['slave']:
            #         if slave_pn in self.__warnings.keys():
            #             row = self.__warnings[slave_pn]
            #             if row['beginTime']:
            #                 begin_time.append(row['beginTime'])
            #             if row['endTime']:
            #                 end_time.append(row['endTime'])
            #         pn_tmp.append(slave_pn)
            #         slave_parts.append(self.__parts[slave_pn])

            if part:
                is_part_dict = isinstance(part, dict)
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
            planning.append(slave['group'] if is_slave_dict else '')
            if part:
                planning.append('Y' if eol_flag else 'N')
                planning.append(part['partsGroup'] if part else '')
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
            else:
                planning.append('')
                planning.append('')
                planning.append('')
                planning.append('')
                planning.append('')
                planning.append('')
            category_id = part['partsGroupId'] if part else ''
            qty_by_ra, qty_by_weight = self.__get_demand(planning, pn_tmp, models, category_id)
            applies = self.__merge_parts_apply(planning, pn_tmp)
            inventories = self.__merge_inventory(planning, pn_tmp)
            shippings = self.__merge_shipping(planning, pn_tmp)
            usages = {}
            demands = {}
            for vendor in self.__vendors:
                usages[vendor['id']] = inventories[vendor['id']] + shippings[vendor['id']] - applies[vendor['id']]
                demands[vendor['id']] = dict()
                demands[vendor['id']]['f'] = qty_by_ra[vendor['id']]
                demands[vendor['id']]['w'] = qty_by_weight[vendor['id']]

            to_values = self.__get_to(planning, usages, demands)

            four_month_shortage = self.__four_month_shortage(planning, to_values, demands, usages)

            final_total_shortage = self.__fcst_shortage(planning, remain, demands, usages)

            hk_inventory = 0
            sql = 'select sum(qty) from Inventory where warehouseId = %s and pn = %s'
            for row in pn_tmp:
                self.cursor.execute(sql, [509, row])
                hk_inventory += int(self.cursor.fetchone().values()[0] or 0)
            planning.append(hk_inventory)

            sh_inventory = 0
            sql = 'select sum(qty) from Inventory where warehouseId = %s and pn = %s'
            for row in pn_tmp:
                self.cursor.execute(sql, [81, row])
                sh_inventory += int(self.cursor.fetchone().values()[0] or 0)
            planning.append(sh_inventory)

            hk_shipping = 0
            sql = 'select sum(ShippingDetails.qty) from ShippingDetails inner join ShippingOrder ' \
                  'on ShippingDetails.shippingOrderId = ShippingOrder.id' \
                  ' where partsPN = %s and destinationWarehouseId = %s and status = %s'
            for row in pn_tmp:
                self.cursor.execute(sql, [row, 509, 1])
                hk_shipping += int(self.cursor.fetchone().values()[0] or 0)
            planning.append(hk_shipping)
            sorts = ["f", "w"]
            for row in sorts:
                planning.append(four_month_shortage[row] - hk_inventory)
            for row in sorts:
                planning.append(four_month_shortage[row] - hk_inventory - sh_inventory)
            for row in sorts:
                planning.append(four_month_shortage[row] - hk_inventory - sh_inventory - hk_shipping)

            for row in sorts:
                planning.append(final_total_shortage[row] - hk_inventory)
            for row in sorts:
                planning.append(final_total_shortage[row] - hk_inventory - sh_inventory)
            for row in sorts:
                planning.append(final_total_shortage[row] - hk_inventory - sh_inventory - hk_shipping)

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
        sql = 'select newPN1,newPN2,newPN3 from ServiceOrder ' \
              'where (newPN1 != "" or newPN2 != "" or newPN3 != "") and deleted = 0'
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
                pns[slave] = {"group": cluster['cluster']}
            #     if slave in pns.keys():
            #         del pns[slave]
        return pns

    def __models(self, pn):
        sql = 'select distinct modelId from PhoneBom where pn = %s'
        self.cursor.execute(sql, [pn])
        models = ','.join([str(bom['modelId']) for bom in self.cursor if bom['modelId']])
        if models:
            sql = 'select distinct modeltype from Model where id in ('+models+')'
            self.cursor.execute(sql)
            return [model['modeltype'] for model in self.cursor]

    def __get_demand(self, planning, pns, models, category_id):
        qty_by_weight = {}
        qty_by_ra = {}

        for vendor in self.__vendors:
            demand_by_mffr = 0
            if models:
                for model in models:
                    rate = self.__get_failure_rate(model, category_id, vendor['id'], self.__months['lastMonth'])
                    warranty = self.__get_model_warranty(model, vendor['id'], self.__months['thisMonth'])
                    warranty = math.ceil(warranty * self.__model_warranty_setting[vendor['id']] / 100)
                    demand_by_mffr += math.ceil(warranty * rate / 100)
            planning.append(demand_by_mffr)
            qty_by_ra[vendor['id']] = demand_by_mffr

            last_3_month = 0
            last_2_month = 0
            last_month = 0
            for pn in pns:
                key = "_".join([pn, vendor['id']])
                last_3_month += self.__usages['last3Month'][key] if key in self.__usages['last3Month'] else 0
                last_2_month += self.__usages['last2Month'][key] if key in self.__usages['last2Month'] else 0
                last_month += self.__usages['lastMonth'][key] if key in self.__usages['lastMonth'] else 0

            demand_by_weight = self.__get_qty_by_weight(last_3_month, last_2_month, last_month)
            planning.append(demand_by_weight)
            qty_by_weight[vendor['id']] = demand_by_weight
        return qty_by_ra, qty_by_weight

    def __get_failure_rate(self, model, category_id, vendor_id, month):
        if category_id is None:
            category_id = ''

        if not self.__failure_rate:
            sql = 'select * from FailureRateModel where month = %s'
            self.cursor.execute(sql, [month])
            for row in self.cursor:
                key = "_".join([row['model'], row['categoryId'], row['country']])
                self.__failure_rate[key] = float(row['rate'])
        key = "_".join([model, category_id, self.__countries[vendor_id]])
        return self.__failure_rate[key] if key in self.__failure_rate.keys() else 0

    def __get_model_warranty(self, model, vendor_id, date):
        if not self.__model_warranty:
            sql = 'select model,country,sum(number) as number from ModelWarranty ' \
                  'where expireTime > %s and salesTime < %s group by model,country'
            year, month = date.split("-")

            day = calendar.monthrange(int(year), int(month))[1]
            month_end = "-".join([str(year), str(month), str(day)])
            self.cursor.execute(sql, [month_end, month_end])
            for row in self.cursor:
                key = "_".join([row['model'], row['country']])
                self.__model_warranty[key] = int(row['number'])

        key = "_".join([model, self.__countries[vendor_id]])
        return self.__model_warranty[key] if key in self.__model_warranty.keys() else 0

    def __get_qty_by_weight(self, first_month, second_month, third_month):
        qty = first_month * (float(self.__basic_data['weight1']) / 100) \
              + second_month * (float(self.__basic_data['weight2']) / 100) \
              + third_month * (float(self.__basic_data['weight3']) / 100)
        return math.ceil(qty)

    def __merge_inventory(self, planning, pns):
        inventories = dict()
        for vendor in self.__vendors:
            qty = 0
            for pn in pns:
                if vendor['id'] in self.__parts_inventory.keys() and pn in self.__parts_inventory[vendor['id']].keys():
                    qty += self.__parts_inventory[vendor['id']][pn]
            planning.append(qty)
            inventories[vendor['id']] = qty
        return inventories

    def __merge_shipping(self, planning, pns):
        shippings = dict()
        for vendor in self.__vendors:
            qty = 0
            for pn in pns:
                if vendor['id'] in self.__parts_shipping.keys() and pn in self.__parts_shipping[vendor['id']].keys():
                    qty += self.__parts_shipping[vendor['id']][pn]
            planning.append(qty)
            shippings[vendor['id']] = qty
        return shippings

    def __merge_parts_apply(self, planning, pns):
        if not self.__applies:
            sql = 'select newPN1,newPN2,newPN3 from ServiceOrder where deleted = 0 and vendorId = %s and status in (%s,%s,%s)'
            for vendor in self.__vendors:
                self.__applies[vendor['id']] = {}
                self.cursor.execute(sql, [vendor['id'], 2, 9, 10])
                for row in self.cursor:
                    if row['newPN1']:
                        if row['newPN1'] not in self.__applies[vendor['id']].keys():
                            self.__applies[vendor['id']][row['newPN1']] = 0
                        self.__applies[vendor['id']][row['newPN1']] += 1
                    if row['newPN2']:
                        if row['newPN2'] not in self.__applies[vendor['id']].keys():
                            self.__applies[vendor['id']][row['newPN2']] = 0
                        self.__applies[vendor['id']][row['newPN2']] += 1
                    if row['newPN3']:
                        if row['newPN3'] not in self.__applies[vendor['id']].keys():
                            self.__applies[vendor['id']][row['newPN3']] = 0
                        self.__applies[vendor['id']][row['newPN3']] += 1

        applies = {}
        for vendor in self.__vendors:
            qty = 0
            for pn in pns:
                if pn in self.__applies[vendor['id']].keys():
                    qty += self.__applies[vendor['id']][pn]
            applies[vendor['id']] = qty
            planning.append(qty)
        return applies

    def __get_to(self, planning, usages, demands):
        sorts = ['f', 'w']
        to_values = dict()
        for vendor in self.__vendors:
            to_values[vendor['id']] = dict()
            usage = int(usages[vendor['id']])
            demand = demands[vendor['id']]
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
                to_values[vendor['id']][row] = to
                planning.append(to)
        return to_values

    def __four_month_shortage(self, planning, to_values, demands, usages):
        sorts = ["f", "w"]
        total = {"f": 0, "w": 0}
        for vendor in self.__vendors:
            for row in sorts:
                to = to_values[vendor['id']][row]
                if to == '9999':
                    planning.append('0')
                elif to == 'N/A':
                    planning.append('')
                else:
                    shortage = round(2.5 * demands[vendor['id']][row] - usages[vendor['id']])
                    total[row] += shortage if shortage > 0 else 0
                    planning.append(shortage)
        return total

    def __fcst_shortage(self, planning, remain, demands, usages):
        sorts = ["f", "w"]
        total = {"f": 0, "w": 0}
        for vendor in self.__vendors:
            for row in sorts:
                shortage = round(remain * demands[vendor['id']][row] - usages[vendor['id']])
                total[row] += shortage if shortage > 0 else 0
                planning.append(shortage)
        return total

    def __get_header_1(self):
        header = []
        for i in range(1, 10):
            header.append('')
        vendor_cnt = len(self.__vendors)
        cnt = vendor_cnt * 2

        header.append('FCST Demand')
        for i in range(1, cnt):
            header.append('')

        header.append('Parts Apply')
        for i in range(1, vendor_cnt):
            header.append('')

        header.append('Inventory')
        for i in range(1, vendor_cnt):
            header.append('')

        header.append('Shipping Order')
        for i in range(1, vendor_cnt):
            header.append('')

        header.append('TO')
        for i in range(1, cnt):
            header.append('')

        header.append('根据2.5个月缺量')
        for i in range(1, cnt):
            header.append('')

        header.append('FCST Shortage')
        for i in range(1, cnt):
            header.append('')

        header.append('')
        header.append('')
        header.append('')
        header.append('2.5 month shortage qty')
        header.append('')
        header.append('')
        header.append('')
        header.append('')
        header.append('')
        header.append('final total shortage qty')

        return header

    def __get_header_2(self):
        header = []
        for i in range(1, 10):
            header.append('')

        for vendor in self.__vendors:
            header.append(vendor['countryShortName'])
            header.append('')

        for vendor in self.__vendors:
            header.append(vendor['countryShortName'])

        for vendor in self.__vendors:
            header.append(vendor['countryShortName'])

        for vendor in self.__vendors:
            header.append(vendor['countryShortName'])

        for vendor in self.__vendors:
            header.append(vendor['countryShortName'])
            header.append('')

        for vendor in self.__vendors:
            header.append(vendor['countryShortName'])
            header.append('')

        for vendor in self.__vendors:
            header.append(vendor['countryShortName'])
            header.append('')

        header.append('HK W/H Good Parts Qty.')
        header.append('SH W/H Good Parts Qty.')
        header.append('Maitrox G/P In Transit Qty.')
        header.append('减去HK WH')
        header.append('')
        header.append('减去SH WH')
        header.append('')
        header.append('减去Maitrox G/P In Transit Qty.')
        header.append('')
        header.append('减去HK WH')
        header.append('')
        header.append('减去SH WH')
        header.append('')
        header.append('减去Maitrox G/P In Transit Qty.')
        header.append('')
        header.append('On Order')
        header.append('PCB在维修数(已在修数)')
        header.append('OMP 已提交需求')
        return header

    def __get_header_3(self):
        header = ['PN', 'ABC Class', 'Group', 'EOL Flag', 'Category', 'Model', 'Remain Warranty Month',
                  'Begin Warranty Time', 'End Warranty Time']
        vendor_cnt = len(self.__vendors)
        for i in range(0, vendor_cnt):
            header.append('by RA')
            header.append('by WT')

        for i in range(0, vendor_cnt):
            header.append('')

        for i in range(0, vendor_cnt):
            header.append('')

        for i in range(0, vendor_cnt):
            header.append('')

        for i in range(0, vendor_cnt):
            header.append('by RA')
            header.append('by WT')

        for i in range(0, vendor_cnt):
            header.append('by RA')
            header.append('by WT')

        for i in range(0, vendor_cnt):
            header.append('by RA')
            header.append('by WT')

        header.append('')
        header.append('')
        header.append('')
        header.append('by RA')
        header.append('by WT')
        header.append('by RA')
        header.append('by WT')
        header.append('by RA')
        header.append('by WT')
        header.append('by RA')
        header.append('by WT')
        header.append('by RA')
        header.append('by WT')
        header.append('by RA')
        header.append('by WT')
        return header

replenish = Replenish()
filename = sys.argv[1]
replenish.reset(filename)