<?php
//$width = 1280;
//$height = 720;

// スマホ 720*1280 9:16
//$width = 300;
//$height = 400;
$width = 720;
$height = 1280;
?>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0;">
<h1>カメラ</h1>
<button onclick="startVideo()">カメラ</button>
<button onclick="startFrame()">フレーム</button>
<button onclick="onShutter()">シャッター</button>
<br />
<div id="box" style="width: 360; height: 640; position: relative; border: 1px solid #000000;">
  <canvas id="frame" width="<?=$width?>" height="<?=$height?>" style="z-index: 100; position: absolute; width: 360; height: 640;"></canvas>
  <video id="local_video" autoplay playsinline width="<?=$width?>" height="<?=$height?>" style="z-index: 1; position: absolute; width: 360; height: 640;" muted></video>
</div>
<br />
<div id="box2" style="width: 360; height: 640; border: 1px solid #000000;">
  <canvas id="still" width="<?=$width?>" height="<?=$height?>" style="width: 360; height: 640;"></canvas>
</div>
<button onclick="save()">サーバーに保存</button>
<br />
<div>
  <img id="result" src="./logs/test.jpg" style="width: 360; height: 640;">
</div>

<script>
let localVideo = document.querySelector('#local_video');
let localStream = null;
let cameraMode = { exact: "environment" };

function startVideo() {
  if (localStream !== null) {
    localStream.getVideoTracks().forEach((camera) => {
      camera.stop();
    });
  }
  if (cameraMode != 'user') {
    cameraMode = 'user';
  } else {
    cameraMode = { exact: "environment" };
  }

  navigator.mediaDevices.getUserMedia({
    video: {
      width: {
        min: 720,
      }, // スマホ
      //height: 1280, // スマホ
      //width: <?=$width?>,
      //height: <?=$height?>,
      //facingMode: "user",
      facingMode: cameraMode,
    },
    audio: false,
  }).then(function (stream) { // success
    localStream = stream;
    localVideo.srcObject = localStream;
  }).catch(function (error) { // error
    console.error('mediaDevice.getUserMedia() error:', error);
    return;
  });
}

function startFrame() {
  const image = new Image();
  image.src = './frame2.png';
  image.onload = () => {
    const frame = document.querySelector("#frame");
    const ctx = frame.getContext("2d");
    ctx.clearRect(0, 0, frame.width, frame.height);
    ctx.drawImage(image, 0, 0, frame.width, frame.height);
  };
}

function onShutter() {
  const localVideo = document.querySelector('#local_video');
  const still = document.querySelector("#still");
  const ctx = still.getContext("2d");
  ctx.clearRect(0, 0, still.width, still.height);
  ctx.drawImage(localVideo, 0, 0, still.width, still.height);
}

function save() {
  const canvas = document.querySelector("#still");
  const image = canvas.toDataURL("image/jpeg");
  const params = {
    method: "POST",
    headers: {
      "Content-Type": "application/json; charset=utf-8"
    },
    body: JSON.stringify({data: image}),
  };
  fetch("receive.php", params)
    .then((response) => {
      return response.json(); // JSONが返される想定
    })
    .then((json) => {
      console.log(json);
      const result = document.querySelector("#result");
      result.setAttribute('src', json.result);
    })
    .catch((error) => {
      alert('error!');
    });
}

window.onload = function() {

}
</script>
</body>
</html>
