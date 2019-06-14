package main

import "time"

// EPOCH 时间偏移量
const EPOCH int64 = 1523980800000

// MAX41BIT 41 位的最大值
const MAX41BIT int64 = 2199023255551

// MAX5BIT 5 位的最大值
const MAX5BIT int64 = 31

// MAX12BIT 12 位的最大值
const MAX12BIT int64 = 4095

// seq 自增序列
var seq int64

// nodeID 节点号
var nodeID int64

// next 获取下一个唯一 ID
func next(serviceID int64) int64 {
	// 取得当前时间戳（毫秒），并减去偏移量
	var timestamp int64
	timestamp = time.Now().UnixNano()/int64(time.Millisecond) - EPOCH
	timestamp <<= 22
	// 节点号
	nodeID &= MAX5BIT
	nodeID <<= 17
	// 服务编号
	serviceID &= MAX5BIT
	serviceID <<= 12
	// 自增序列
	seq &= MAX12BIT
	seq++

	return timestamp | nodeID | serviceID | seq
}
