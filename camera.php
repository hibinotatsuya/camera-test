<?php
$width = 640;
$height = 480;
//$width = 300;
//$height = 400;
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
<div id="box" style="width: <?=$width?>; height: <?=$height?>; position: relative; border: 1px solid #000000;">
  <canvas id="frame" width="<?=$width?>" height="<?=$height?>" style="z-index: 100; position: absolute;"></canvas>
  <video id="local_video" autoplay playsinline width="<?=$width?>" height="<?=$height?>" style="z-index: 1; position: absolute;" muted></video>
</div>
<div id="box2" style="width: <?=$width?>; height: <?=$height?>; border: 1px solid #000000;">
  <canvas id="still" width="<?=$width?>" height="<?=$height?>"></canvas>
</div>

<script>
function startVideo() {
  let localVideo = document.querySelector('#local_video');
  let localStream;

  navigator.mediaDevices.getUserMedia({
    video: {
	  width: <?=$width?>,
	  height: <?=$height?>,
	  facingMode: "user"
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

window.onload = function() {

}
</script>
</body>
</html>
