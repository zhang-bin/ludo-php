<?php
class RankModel {
    const RANK_MAX_MEMBER = 10000;

    /**
     * 把分数跟当前的时间戳做运算,得到新的分数
     *
     * @param $score
     * @return mixed
     */
    public static function getNewScore($score) {
        return $score * BEST_SCORE_KEY_BASE + BEST_SCORE_KEY_TIMESTAMP - time();
    }

    /**
     * 还原分数
     *
     * @param $score
     * @return float
     */
    public static function getActualScore($score) {
        return floor($score / BEST_SCORE_KEY_BASE);
    }

    /**
     * 更新排行榜
     *
     * @param $scoreKey
     * @param $rankKey
     * @param $member
     * @param $addScore
     * @param $max
     * @return int
     */
    public static function updateRank($scoreKey, $rankKey, $member, $addScore, $max = self::RANK_MAX_MEMBER) {
        $cache = Cache::redis();
        $newScore = $cache->hIncrBy($scoreKey, $member, $addScore);

        if ($cache->zCard($rankKey) < $max) { //前10000名没有排满
            $cache->zAdd($rankKey, self::getNewScore($newScore), $member);
        } else {
            $last = $cache->zRange($rankKey, 0, 1, true);//最后一名
            $lastHashKey = key($last);
            $lastScore = $last[$lastHashKey];
            if ($newScore > RankModel::getActualScore($lastScore)) {
                //超过了最后一名，把最后一名给移出排行榜
                $cache->zDelete($rankKey, $lastHashKey);
                $cache->zAdd($rankKey, RankModel::getNewScore($newScore), $member); //将玩家分数插入排行榜
            }
        }
        return $newScore;
    }

    /**
     * 获取排行榜数据
     *
     * @param $rankKey
     * @return array
     */
    public static function getRanks($rankKey) {
        $cache = Cache::redis();
        return $cache->zRevRange($rankKey, 0, 99, true);
    }

    /**
     * 我的排名和分数
     *
     * @param $scoreKey
     * @param $rankKey
     * @param $member
     * @return array
     */
    public static function getMyRank($scoreKey, $rankKey, $member) {
        $cache = Cache::redis();
        $score = $cache->hGet($scoreKey, $member);
        if (empty($score)) $score = null;

        $rank = $cache->zRevRank($rankKey, $member);
        if ($rank === false || is_null($rank)) {
            $rank = null;
        } else {
            $rank++;
        }
        return array($score, $rank);
    }

    /**
     * 分数增加
     *
     * @param $rankKey
     * @param $member
     * @param $addScore
     * @return array
     */
    public static function scoreIncrease($rankKey, $member, $addScore) {
        $cache = Cache::redis();
        $oldScore = $cache->zScore($rankKey, $member);
        if (is_null($oldScore)) {
            $isFirst = true;
            $score = 0;
        } else {
            $score = self::getActualScore($oldScore);
            $isFirst = false;
        }
        $score += $addScore;
        $newScore = self::getNewScore($score);
        $cache->zIncrBy($rankKey, $newScore - $oldScore, $member);

        return array('isFirst' => $isFirst, 'score' => $score);
    }
}