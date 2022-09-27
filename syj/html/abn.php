<html> 
    <html lang="ko">
        <head> 
            <meta charset="utf-8">
            <script type="text/javascript" src="https://static.robotwebtools.org/roslibjs/current/roslib.min.js">
                </script>
</head> 

<body> 
<?php
include 'config.php';
#include '/home/dd/바탕화면/python/kakao';
date_default_timezone_set('Asia/Seoul'); //시간설정
$done = $_POST['abn'];
$run = 0; //DB에서 select한 이상감지 데이터가 있을때 js로 전달할 변수 초기화

//이상감지 데이터를 select하여 변수로 저장하는 파일
if($con){
  //로봇 명령을 위한 데이터 조회(이상감지) 최근 2초
  $sel_acc = "SELECT acc FROM cam_data WHERE label='fall' AND acc BETWEEN 0.8 AND date BETWEEN DATE_ADD(now(), INTERVAL -2 second) AND now();";
  $sel_fire = "SELECT * FROM data WHERE type='fire_sensor' AND val=1 AND date BETWEEN DATE_ADD(now(), INTERVAL -2 second) AND now();";
  $sel_done = "SELECT * FROM data WHERE type='done' AND val=3 AND date BETWEEN DATE_ADD(now(), INTERVAL -2 second) AND now();";

  $res_acc = mysqli_query($con, $sel_acc);
  $res_fire = mysqli_query($con, $sel_fire);
  $res_done = mysqli_query($con, $sel_done);

  //이상감지가 됐을 경우
  if(mysqli_num_rows($res_acc)>=3) {$abn_acc='abn_acc'; $run=1;} else $abn_acc=null;
  if(mysqli_num_rows($res_fire)) {$abn_fire='abn_fire'; $run=2;} else $abn_fire=null;
  if(mysqli_num_rows($res_done)) {$abn_done='abn_done'; $run=3;} else $abn_done=null;

  //카카오톡 전송 API  
  // if(mysqli_num_rows($res_sensor))
	//   exec("cd /home/dd/바탕화면/python/ && python3 kakao_run.py"); 
  echo json_encode(array($abn_acc, $abn_fire,$abn_done,$nulll));
}
else
        die ("Connection failed: ". mysqli_connect_error());
mysqli_close($con);
?>

<script type="text/javascript">
var jrun = <?php echo $run; ?>;
//웹서버(php)로 부터 변수를 받아옴

// ROS와 connect 및 확인
var ros = new ROSLIB.Ros({
url : 'ws://192.168.137.246:9090'
});
ros.on('connection', function() {
console.log('Connected to websocket server.');
});

ros.on('error', function(error) {
console.log('Error connecting to websocket server: ', error);
});

ros.on('close', function() {
console.log('Connection to websocket server closed.');
});

var goal = new ROSLIB.Topic({
ros : ros,
name : '/move_base/goal', // /cmd_vel
messageType : 'move_base_msgs/MoveBaseGoal' // 'geometry_msgs/Twist'
})

switch (jrun) {

case 1: //사람쓰러졌을때(카메라 앞)
    var moveBaseGoal = new ROSLIB.Message({
        goal: {
            target_pose: {
                header: {
                    frame_id : "map"
                },
                pose: {
                    position: {
                        x : 4.66,
                        y : 0.76,
                        z : 0.0
                    },
                    orientation: {
                        x : 0.0,
                        y : 0.0,
                        z :0.0,
                        w : 1.0
                    }
                }
            }
        }
    })
    break;

case 2: //대피로(화재 탈출)
var moveBaseGoal = new ROSLIB.Message({
    goal: {
        target_pose: {
            header: {
                frame_id : "map"
            },
            pose: {
                position: {
                    x : 2.7,
                    y : -3.01,
                    z : 0.0
                },
                orientation: {
                    x : 0.0,
                    y : 0.0,
                    z :0.0,
                    w : 1.0
                }
            }
        }
    }
})
break;

case 3: //초기위치
    var moveBaseGoal = new ROSLIB.Message({
        goal: {
            target_pose: {
                header: {
                    frame_id : "map"
                },
                pose: {
                    position: {
                        x : 2.74,
                        y : 0.79,
                        z : 0.0
                    },
                    orientation: {
                        x : 0.0,
                        y : 0.0,
                        z :0.0,
                        w : 1.0
                    }
                }
            }
        }
    })
    break;
default:
    break;
}

//goal.publish(moveBaseGoal) //선택된 case의 데이터를 ROS에 전달(발행)

</script>
</body>

</html>
