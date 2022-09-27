<?php
//error_reporting(E_ALL);
// 데이터 베이스 연결을 위한 파일
// DB연동이 필요한 파일에 include 'config.php' 입력해서 사용;

// DB 정보
$host = "localhost";
$user = "root";
$password = "root1234";
$dbname = "iot";

// DB 연동
$con = mysqli_connect($host, $user, $password, $dbname);

// DB 연동 확인
if (!$con){   
  die ("Connection failed: ". mysqli_connect_error());    // 연결이 안될경우 이 코드 실행
}
