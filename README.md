# lumen-gRPC-demo
lumen、grpc、protobuf、golang 

#### 客户端目录 lumen-gRPC-demo

##### 启动客户端
##### 1.搭建nginx，目录指向lumen-gRPC-demo/public
##### 2.使用postman 发送请求，路由地址：{域名或者ip}/snow

#### 服务端目录 lumen-grpc-demo/grpc

##### 启动服务端  
##### 1.运行 ./build.sh （编译golang）   
##### 2.进入bin 文件夹,运行  ./snowflake-grpc-server 

#### 环境要求
##### 1.golang 语言环境  && php 语言环境
##### 2.nginx 
##### 3.protoc 以及protoc-gen-grpc 插件
##### 4.必要的golang 包，grpc 

#### protoc 编译命令
protoc --proto_path=./ --php_out=./ --grpc_out=./ --plugin=protoc-gen-grpc=/home/l/grpc/bins/opt/grpc_php_plugin snow.proto
protoc --go_out=plugins=grpc:. snow.proto

