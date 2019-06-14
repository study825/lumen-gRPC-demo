package main

import (
	"context"
	"fmt"
	"net"

	"lumen-gRPC-demo/grpc/proto"
	"google.golang.org/grpc"
)

// SnowflakeService Snowflake gRPC 服务
type SnowflakeService struct{}

// Next 获取下一个唯一 id
func (s SnowflakeService) Next(ctx context.Context, req *proto.NextRequest) (*proto.NextReply, error) {
	res := new(proto.NextReply)
	res.Id = next(req.ServiceId)
	return res, nil
}


func main() {
	//指定我们期望客户端请求的监听端口。
	Addr := "127.0.0.1:6666"
	listen, err := net.Listen("tcp", Addr)
	if err != nil {
		fmt.Printf("Failed to listen: %v, program exited\n", err)
		return
	}
	//创建 gRPC 服务器的一个实例。
	server := grpc.NewServer()
	//在 gRPC 服务器注册我们的服务实现。
	var snowflakeService SnowflakeService
	proto.RegisterSnowflakeServer(server, snowflakeService)
	fmt.Println("Listen on: " + Addr)
	server.Serve(listen)
}
