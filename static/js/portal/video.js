$(document).ready(function() {
    loadVideo("/static/files/entidad/1-Solicitud Uno a Uno.mp4", "cvnVideoOne");
    loadVideoTwo("/static/files/entidad/2-Solicitud masiva.mp4", "cvnVideoTwo");
    loadVideoThree("/static/files/entidad/3-Activación de pedido.mp4", "cvnVideoThree");
    loadVideoFour("/static/files/entidad/4-Abonos Uno a uno.mp4", "cvnVideoFour");
    loadVideoFive("/static/files/entidad/5-Abonos masivos.mp4", "cvnVideoFive");
    loadVideoSix("/static/files/entidad/6-Pago de mis pedidos.mp4", "cvnVideoSix");
    loadVideoSeven("/static/files/entidad/7-Asignar roles y administrar usuarios.mp4", "cvnVideoSeven");


    function loadVideo(url, canva) {
        var mediaSource = url;
        var canvas;
        var ctx;
        var videoContainer;
        var video;
        var divPatern = canva;
        var muted = false;
        canvas = document.getElementById(canva);
        ctx = canvas.getContext("2d");
        video = document.createElement("video");
        video.src = mediaSource;
        video.autoPlay = false;
        video.loop = true;
        video.muted = muted;
        videoContainer = {
            video: video,
            ready: false,
        };
        video.onerror = function(e) {
            document.body.removeChild(canvas);
            document.body.innerHTML += "<h2>There is a problem loading the video</h2><br>";
            document.body.innerHTML += "Users of IE9+ , the browser does not support WebM videos used by this demo";
            document.body.innerHTML += "<br><a href='https://tools.google.com/dlpage/webmmf/'> Download IE9+ WebM support</a> from tools.google.com<br> this includes Edge and Windows 10";

        }

        video.oncanplay = readyToPlayVideo;

        function readyToPlayVideo(event) { // this is a referance to the video
            videoContainer.scale = Math.min(
                canvas.width / this.videoWidth,
                canvas.height / this.videoHeight);
            videoContainer.ready = true;
            requestAnimationFrame(updateCanvas);
            document.getElementById("playPausecvnVideoOne").textContent = " Clic sobre video para reproducir o pausar.";
            document.querySelector(".mutecvnVideoOne").textContent = "Silencio";
        }

        /// these will return dimensions in *pixel* regardless of what
        /// you originally specified for image:


        function updateCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            if (videoContainer !== undefined && videoContainer.ready) {
                video.muted = muted;
                var scale = videoContainer.scale;
                var vidH = videoContainer.video.videoHeight;
                var vidW = videoContainer.video.videoWidth;
                var top = canvas.height / 2 - (vidH / 2) * scale;
                var left = canvas.width / 2 - (vidW / 2) * scale;
                ctx.drawImage(videoContainer.video, left, top, vidW * scale, vidH * scale);
                if (videoContainer.video.paused) { // if not playing show the paused screen 
                    drawPayIcon();
                }
            }
            // all done for display 
            // request the next frame in 1/60th of a second
            requestAnimationFrame(updateCanvas);
        }

        function drawPayIcon() {
            ctx.fillStyle = "black"; // darken display
            ctx.globalAlpha = 0.5;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#DDD"; // colour of play icon
            ctx.globalAlpha = 0.75; // partly transparent
            ctx.beginPath(); // create the path for the icon
            var size = (canvas.height / 2) * 0.5; // the size of the icon
            ctx.moveTo(canvas.width / 2 + size / 2, canvas.height / 2); // start at the pointy end
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 + size);
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 - size);
            ctx.closePath();
            ctx.fill();
            ctx.globalAlpha = 1; // restore alpha
        }

        function playPauseClick() {
            if (videoContainer !== undefined && videoContainer.ready) {
                if (videoContainer.video.paused) {
                    videoContainer.video.play();
                } else {
                    videoContainer.video.pause();
                }
            }
        }

        function videoMute() {
            muted = !muted;
            if (muted) {
                document.querySelector(".mute" + divPatern).textContent = "Mute";
            } else {
                document.querySelector(".mute" + divPatern).textContent = "Sound on";
            }
        }
        canvas.addEventListener("click", playPauseClick);
        document.querySelector(".mute" + divPatern).addEventListener("click", videoMute);

        // Enable Carousel Controls
        $(".left").click(function() {
            $("#lightbox").carousel("prev");
            videoContainer.video.pause();
        });
        $(".right").click(function() {
            $("#lightbox").carousel("next");
            videoContainer.video.pause();

        });
        $('#lightbox').on('hidden.bs.modal', function() {
            videoContainer.video.pause();
        })
    }

    function loadVideoTwo(url, canva) {
        var mediaSource = url;
        var canvas;
        var ctx;
        var videoContainer;
        var video;
        var divPatern = canva;
        var muted = false;
        canvas = document.getElementById(canva);
        ctx = canvas.getContext("2d");
        video = document.createElement("video");
        video.src = mediaSource;
        video.autoPlay = false;
        video.loop = true;
        video.muted = muted;
        videoContainer = {
            video: video,
            ready: false,
        };
        video.onerror = function(e) {
            document.body.removeChild(canvas);
            document.body.innerHTML += "<h2>There is a problem loading the video</h2><br>";
            document.body.innerHTML += "Users of IE9+ , the browser does not support WebM videos used by this demo";
            document.body.innerHTML += "<br><a href='https://tools.google.com/dlpage/webmmf/'> Download IE9+ WebM support</a> from tools.google.com<br> this includes Edge and Windows 10";

        }
        video.oncanplay = readyToPlayVideo;

        function readyToPlayVideo(event) { // this is a referance to the video
            videoContainer.scale = Math.min(
                canvas.width / this.videoWidth,
                canvas.height / this.videoHeight);
            videoContainer.ready = true;
            requestAnimationFrame(updateCanvas);
            document.getElementById("playPausecvnVideoTwo").textContent = " Clic sobre video para reproducir o pausar.";
            document.querySelector(".mutecvnVideoTwo").textContent = "Silencio";
        }

        function updateCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            if (videoContainer !== undefined && videoContainer.ready) {
                video.muted = muted;
                var scale = videoContainer.scale;
                var vidH = videoContainer.video.videoHeight;
                var vidW = videoContainer.video.videoWidth;
                var top = canvas.height / 2 - (vidH / 2) * scale;
                var left = canvas.width / 2 - (vidW / 2) * scale;
                ctx.drawImage(videoContainer.video, left, top, vidW * scale, vidH * scale);
                if (videoContainer.video.paused) { // if not playing show the paused screen 
                    drawPayIcon();
                }
            }
            // all done for display 
            // request the next frame in 1/60th of a second
            requestAnimationFrame(updateCanvas);
        }

        function drawPayIcon() {
            ctx.fillStyle = "black"; // darken display
            ctx.globalAlpha = 0.5;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#DDD"; // colour of play icon
            ctx.globalAlpha = 0.75; // partly transparent
            ctx.beginPath(); // create the path for the icon
            var size = (canvas.height / 2) * 0.5; // the size of the icon
            ctx.moveTo(canvas.width / 2 + size / 2, canvas.height / 2); // start at the pointy end
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 + size);
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 - size);
            ctx.closePath();
            ctx.fill();
            ctx.globalAlpha = 1; // restore alpha
        }

        function playPauseClick() {
            if (videoContainer !== undefined && videoContainer.ready) {
                if (videoContainer.video.paused) {
                    videoContainer.video.play();
                } else {
                    videoContainer.video.pause();
                }
            }
        }

        function videoMute() {
            muted = !muted;
            if (muted) {
                document.querySelector(".mute" + divPatern).textContent = "Mute";
            } else {
                document.querySelector(".mute" + divPatern).textContent = "Sound on";
            }
        }
        canvas.addEventListener("click", playPauseClick);
        document.querySelector(".mute" + divPatern).addEventListener("click", videoMute);

        $(".left").click(function() {
            $("#lightbox").carousel("prev");
            videoContainer.video.pause();
        });
        $(".right").click(function() {
            $("#lightbox").carousel("next");
            videoContainer.video.pause();

        });
        $('#lightbox').on('hidden.bs.modal', function() {
            videoContainer.video.pause();
        })
    }

    function loadVideoThree(url, canva) {
        var mediaSource = url;
        var canvas;
        var ctx;
        var videoContainer;
        var video;
        var divPatern = canva;
        var muted = false;
        canvas = document.getElementById(canva);
        ctx = canvas.getContext("2d");
        video = document.createElement("video");
        video.src = mediaSource;
        video.autoPlay = false;
        video.loop = true;
        video.muted = muted;
        videoContainer = {
            video: video,
            ready: false,
        };
        video.onerror = function(e) {
            document.body.removeChild(canvas);
            document.body.innerHTML += "<h2>There is a problem loading the video</h2><br>";
            document.body.innerHTML += "Users of IE9+ , the browser does not support WebM videos used by this demo";
            document.body.innerHTML += "<br><a href='https://tools.google.com/dlpage/webmmf/'> Download IE9+ WebM support</a> from tools.google.com<br> this includes Edge and Windows 10";

        }
        video.oncanplay = readyToPlayVideo;

        function readyToPlayVideo(event) { // this is a referance to the video
            videoContainer.scale = Math.min(
                canvas.width / this.videoWidth,
                canvas.height / this.videoHeight);
            videoContainer.ready = true;
            requestAnimationFrame(updateCanvas);
            document.getElementById("playPausecvnVideoThree").textContent = " Clic sobre video para reproducir o pausar.";
            document.querySelector(".mutecvnVideoThree").textContent = "Silencio";
        }

        function updateCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            if (videoContainer !== undefined && videoContainer.ready) {
                video.muted = muted;
                var scale = videoContainer.scale;
                var vidH = videoContainer.video.videoHeight;
                var vidW = videoContainer.video.videoWidth;
                var top = canvas.height / 2 - (vidH / 2) * scale;
                var left = canvas.width / 2 - (vidW / 2) * scale;
                ctx.drawImage(videoContainer.video, left, top, vidW * scale, vidH * scale);
                if (videoContainer.video.paused) { // if not playing show the paused screen 
                    drawPayIcon();
                }
            }
            // all done for display 
            // request the next frame in 1/60th of a second
            requestAnimationFrame(updateCanvas);
        }

        function drawPayIcon() {
            ctx.fillStyle = "black"; // darken display
            ctx.globalAlpha = 0.5;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#DDD"; // colour of play icon
            ctx.globalAlpha = 0.75; // partly transparent
            ctx.beginPath(); // create the path for the icon
            var size = (canvas.height / 2) * 0.5; // the size of the icon
            ctx.moveTo(canvas.width / 2 + size / 2, canvas.height / 2); // start at the pointy end
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 + size);
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 - size);
            ctx.closePath();
            ctx.fill();
            ctx.globalAlpha = 1; // restore alpha
        }

        function playPauseClick() {
            if (videoContainer !== undefined && videoContainer.ready) {
                if (videoContainer.video.paused) {
                    videoContainer.video.play();
                } else {
                    videoContainer.video.pause();
                }
            }
        }

        function videoMute() {
            muted = !muted;
            if (muted) {
                document.querySelector(".mute" + divPatern).textContent = "Mute";
            } else {
                document.querySelector(".mute" + divPatern).textContent = "Sound on";
            }
        }
        canvas.addEventListener("click", playPauseClick);
        document.querySelector(".mute" + divPatern).addEventListener("click", videoMute);

        $(".left").click(function() {
            $("#lightbox").carousel("prev");
            videoContainer.video.pause();
        });
        $(".right").click(function() {
            $("#lightbox").carousel("next");
            videoContainer.video.pause();

        });
        $('#lightbox').on('hidden.bs.modal', function() {
            videoContainer.video.pause();
        })
    }

    function loadVideoFour(url, canva) {
        var mediaSource = url;
        var canvas;
        var ctx;
        var videoContainer;
        var video;
        var divPatern = canva;
        var muted = false;
        canvas = document.getElementById(canva);
        ctx = canvas.getContext("2d");
        video = document.createElement("video");
        video.src = mediaSource;
        video.autoPlay = false;
        video.loop = true;
        video.muted = muted;
        videoContainer = {
            video: video,
            ready: false,
        };
        video.onerror = function(e) {
            document.body.removeChild(canvas);
            document.body.innerHTML += "<h2>There is a problem loading the video</h2><br>";
            document.body.innerHTML += "Users of IE9+ , the browser does not support WebM videos used by this demo";
            document.body.innerHTML += "<br><a href='https://tools.google.com/dlpage/webmmf/'> Download IE9+ WebM support</a> from tools.google.com<br> this includes Edge and Windows 10";

        }
        video.oncanplay = readyToPlayVideo;

        function readyToPlayVideo(event) { // this is a referance to the video
            videoContainer.scale = Math.min(
                canvas.width / this.videoWidth,
                canvas.height / this.videoHeight);
            videoContainer.ready = true;
            requestAnimationFrame(updateCanvas);
            document.getElementById("playPausecvnVideoFour").textContent = " Clic sobre video para reproducir o pausar.";
            document.querySelector(".mutecvnVideoFour").textContent = "Silencio";
        }

        function updateCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            if (videoContainer !== undefined && videoContainer.ready) {
                video.muted = muted;
                var scale = videoContainer.scale;
                var vidH = videoContainer.video.videoHeight;
                var vidW = videoContainer.video.videoWidth;
                var top = canvas.height / 2 - (vidH / 2) * scale;
                var left = canvas.width / 2 - (vidW / 2) * scale;
                ctx.drawImage(videoContainer.video, left, top, vidW * scale, vidH * scale);
                if (videoContainer.video.paused) { // if not playing show the paused screen 
                    drawPayIcon();
                }
            }
            // all done for display 
            // request the next frame in 1/60th of a second
            requestAnimationFrame(updateCanvas);
        }

        function drawPayIcon() {
            ctx.fillStyle = "black"; // darken display
            ctx.globalAlpha = 0.5;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#DDD"; // colour of play icon
            ctx.globalAlpha = 0.75; // partly transparent
            ctx.beginPath(); // create the path for the icon
            var size = (canvas.height / 2) * 0.5; // the size of the icon
            ctx.moveTo(canvas.width / 2 + size / 2, canvas.height / 2); // start at the pointy end
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 + size);
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 - size);
            ctx.closePath();
            ctx.fill();
            ctx.globalAlpha = 1; // restore alpha
        }

        function playPauseClick() {
            if (videoContainer !== undefined && videoContainer.ready) {
                if (videoContainer.video.paused) {
                    videoContainer.video.play();
                } else {
                    videoContainer.video.pause();
                }
            }
        }

        function videoMute() {
            muted = !muted;
            if (muted) {
                document.querySelector(".mute" + divPatern).textContent = "Mute";
            } else {
                document.querySelector(".mute" + divPatern).textContent = "Sound on";
            }
        }
        canvas.addEventListener("click", playPauseClick);
        document.querySelector(".mute" + divPatern).addEventListener("click", videoMute);

        $(".left").click(function() {
            $("#lightbox").carousel("prev");
            videoContainer.video.pause();
        });
        $(".right").click(function() {
            $("#lightbox").carousel("next");
            videoContainer.video.pause();

        });
        $('#lightbox').on('hidden.bs.modal', function() {
            videoContainer.video.pause();
        })
    }

    function loadVideoFive(url, canva) {
        var mediaSource = url;
        var canvas;
        var ctx;
        var videoContainer;
        var video;
        var divPatern = canva;
        var muted = false;
        canvas = document.getElementById(canva);
        ctx = canvas.getContext("2d");
        video = document.createElement("video");
        video.src = mediaSource;
        video.autoPlay = false;
        video.loop = true;
        video.muted = muted;
        videoContainer = {
            video: video,
            ready: false,
        };
        video.onerror = function(e) {
            document.body.removeChild(canvas);
            document.body.innerHTML += "<h2>There is a problem loading the video</h2><br>";
            document.body.innerHTML += "Users of IE9+ , the browser does not support WebM videos used by this demo";
            document.body.innerHTML += "<br><a href='https://tools.google.com/dlpage/webmmf/'> Download IE9+ WebM support</a> from tools.google.com<br> this includes Edge and Windows 10";

        }
        video.oncanplay = readyToPlayVideo;

        function readyToPlayVideo(event) { // this is a referance to the video
            videoContainer.scale = Math.min(
                canvas.width / this.videoWidth,
                canvas.height / this.videoHeight);
            videoContainer.ready = true;
            requestAnimationFrame(updateCanvas);
            document.getElementById("playPausecvnVideoFive").textContent = " Clic sobre video para reproducir o pausar.";
            document.querySelector(".mutecvnVideoFive").textContent = "Silencio";
        }

        function updateCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            if (videoContainer !== undefined && videoContainer.ready) {
                video.muted = muted;
                var scale = videoContainer.scale;
                var vidH = videoContainer.video.videoHeight;
                var vidW = videoContainer.video.videoWidth;
                var top = canvas.height / 2 - (vidH / 2) * scale;
                var left = canvas.width / 2 - (vidW / 2) * scale;
                ctx.drawImage(videoContainer.video, left, top, vidW * scale, vidH * scale);
                if (videoContainer.video.paused) { // if not playing show the paused screen 
                    drawPayIcon();
                }
            }
            // all done for display 
            // request the next frame in 1/60th of a second
            requestAnimationFrame(updateCanvas);
        }

        function drawPayIcon() {
            ctx.fillStyle = "black"; // darken display
            ctx.globalAlpha = 0.5;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#DDD"; // colour of play icon
            ctx.globalAlpha = 0.75; // partly transparent
            ctx.beginPath(); // create the path for the icon
            var size = (canvas.height / 2) * 0.5; // the size of the icon
            ctx.moveTo(canvas.width / 2 + size / 2, canvas.height / 2); // start at the pointy end
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 + size);
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 - size);
            ctx.closePath();
            ctx.fill();
            ctx.globalAlpha = 1; // restore alpha
        }

        function playPauseClick() {
            if (videoContainer !== undefined && videoContainer.ready) {
                if (videoContainer.video.paused) {
                    videoContainer.video.play();
                } else {
                    videoContainer.video.pause();
                }
            }
        }

        function videoMute() {
            muted = !muted;
            if (muted) {
                document.querySelector(".mute" + divPatern).textContent = "Mute";
            } else {
                document.querySelector(".mute" + divPatern).textContent = "Sound on";
            }
        }
        canvas.addEventListener("click", playPauseClick);
        document.querySelector(".mute" + divPatern).addEventListener("click", videoMute);

        $(".left").click(function() {
            $("#lightbox").carousel("prev");
            videoContainer.video.pause();
        });
        $(".right").click(function() {
            $("#lightbox").carousel("next");
            videoContainer.video.pause();

        });
        $('#lightbox').on('hidden.bs.modal', function() {
            videoContainer.video.pause();
        })
    }

    function loadVideoSix(url, canva) {
        var mediaSource = url;
        var canvas;
        var ctx;
        var videoContainer;
        var video;
        var divPatern = canva;
        var muted = false;
        canvas = document.getElementById(canva);
        ctx = canvas.getContext("2d");
        video = document.createElement("video");
        video.src = mediaSource;
        video.autoPlay = false;
        video.loop = true;
        video.muted = muted;
        videoContainer = {
            video: video,
            ready: false,
        };
        video.onerror = function(e) {
            document.body.removeChild(canvas);
            document.body.innerHTML += "<h2>There is a problem loading the video</h2><br>";
            document.body.innerHTML += "Users of IE9+ , the browser does not support WebM videos used by this demo";
            document.body.innerHTML += "<br><a href='https://tools.google.com/dlpage/webmmf/'> Download IE9+ WebM support</a> from tools.google.com<br> this includes Edge and Windows 10";

        }
        video.oncanplay = readyToPlayVideo;

        function readyToPlayVideo(event) { // this is a referance to the video
            videoContainer.scale = Math.min(
                canvas.width / this.videoWidth,
                canvas.height / this.videoHeight);
            videoContainer.ready = true;
            requestAnimationFrame(updateCanvas);
            document.getElementById("playPausecvnVideoSix").textContent = " Clic sobre video para reproducir o pausar.";
            document.querySelector(".mutecvnVideoSix").textContent = "Silencio";
        }

        function updateCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            if (videoContainer !== undefined && videoContainer.ready) {
                video.muted = muted;
                var scale = videoContainer.scale;
                var vidH = videoContainer.video.videoHeight;
                var vidW = videoContainer.video.videoWidth;
                var top = canvas.height / 2 - (vidH / 2) * scale;
                var left = canvas.width / 2 - (vidW / 2) * scale;
                ctx.drawImage(videoContainer.video, left, top, vidW * scale, vidH * scale);
                if (videoContainer.video.paused) { // if not playing show the paused screen 
                    drawPayIcon();
                }
            }
            // all done for display 
            // request the next frame in 1/60th of a second
            requestAnimationFrame(updateCanvas);
        }

        function drawPayIcon() {
            ctx.fillStyle = "black"; // darken display
            ctx.globalAlpha = 0.5;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#DDD"; // colour of play icon
            ctx.globalAlpha = 0.75; // partly transparent
            ctx.beginPath(); // create the path for the icon
            var size = (canvas.height / 2) * 0.5; // the size of the icon
            ctx.moveTo(canvas.width / 2 + size / 2, canvas.height / 2); // start at the pointy end
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 + size);
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 - size);
            ctx.closePath();
            ctx.fill();
            ctx.globalAlpha = 1; // restore alpha
        }

        function playPauseClick() {
            if (videoContainer !== undefined && videoContainer.ready) {
                if (videoContainer.video.paused) {
                    videoContainer.video.play();
                } else {
                    videoContainer.video.pause();
                }
            }
        }

        function videoMute() {
            muted = !muted;
            if (muted) {
                document.querySelector(".mute" + divPatern).textContent = "Mute";
            } else {
                document.querySelector(".mute" + divPatern).textContent = "Sound on";
            }
        }
        canvas.addEventListener("click", playPauseClick);
        document.querySelector(".mute" + divPatern).addEventListener("click", videoMute);

        $(".left").click(function() {
            $("#lightbox").carousel("prev");
            videoContainer.video.pause();
        });
        $(".right").click(function() {
            $("#lightbox").carousel("next");
            videoContainer.video.pause();

        });
        $('#lightbox').on('hidden.bs.modal', function() {
            videoContainer.video.pause();
        })
    }

    function loadVideoSeven(url, canva) {
        var mediaSource = url;
        var canvas;
        var ctx;
        var videoContainer;
        var video;
        var divPatern = canva;
        var muted = false;
        canvas = document.getElementById(canva);
        ctx = canvas.getContext("2d");
        video = document.createElement("video");
        video.src = mediaSource;
        video.autoPlay = false;
        video.loop = true;
        video.muted = muted;
        videoContainer = {
            video: video,
            ready: false,
        };
        video.onerror = function(e) {
            document.body.removeChild(canvas);
            document.body.innerHTML += "<h2>There is a problem loading the video</h2><br>";
            document.body.innerHTML += "Users of IE9+ , the browser does not support WebM videos used by this demo";
            document.body.innerHTML += "<br><a href='https://tools.google.com/dlpage/webmmf/'> Download IE9+ WebM support</a> from tools.google.com<br> this includes Edge and Windows 10";

        }
        video.oncanplay = readyToPlayVideo;

        function readyToPlayVideo(event) { // this is a referance to the video
            videoContainer.scale = Math.min(
                canvas.width / this.videoWidth,
                canvas.height / this.videoHeight);
            videoContainer.ready = true;
            requestAnimationFrame(updateCanvas);
            document.getElementById("playPausecvnVideoSeven").textContent = " Clic sobre video para reproducir o pausar.";
            document.querySelector(".mutecvnVideoSeven").textContent = "Silencio";
        }

        function updateCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            if (videoContainer !== undefined && videoContainer.ready) {
                video.muted = muted;
                var scale = videoContainer.scale;
                var vidH = videoContainer.video.videoHeight;
                var vidW = videoContainer.video.videoWidth;
                var top = canvas.height / 2 - (vidH / 2) * scale;
                var left = canvas.width / 2 - (vidW / 2) * scale;
                ctx.drawImage(videoContainer.video, left, top, vidW * scale, vidH * scale);
                if (videoContainer.video.paused) { // if not playing show the paused screen 
                    drawPayIcon();
                }
            }
            // all done for display 
            // request the next frame in 1/60th of a second
            requestAnimationFrame(updateCanvas);
        }

        function drawPayIcon() {
            ctx.fillStyle = "black"; // darken display
            ctx.globalAlpha = 0.5;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#DDD"; // colour of play icon
            ctx.globalAlpha = 0.75; // partly transparent
            ctx.beginPath(); // create the path for the icon
            var size = (canvas.height / 2) * 0.5; // the size of the icon
            ctx.moveTo(canvas.width / 2 + size / 2, canvas.height / 2); // start at the pointy end
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 + size);
            ctx.lineTo(canvas.width / 2 - size / 2, canvas.height / 2 - size);
            ctx.closePath();
            ctx.fill();
            ctx.globalAlpha = 1; // restore alpha
        }

        function playPauseClick() {
            if (videoContainer !== undefined && videoContainer.ready) {
                if (videoContainer.video.paused) {
                    videoContainer.video.play();
                } else {
                    videoContainer.video.pause();
                }
            }
        }

        function videoMute() {
            muted = !muted;
            if (muted) {
                document.querySelector(".mute" + divPatern).textContent = "Mute";
            } else {
                document.querySelector(".mute" + divPatern).textContent = "Sound on";
            }
        }
        canvas.addEventListener("click", playPauseClick);
        document.querySelector(".mute" + divPatern).addEventListener("click", videoMute);

        $(".left").click(function() {
            $("#lightbox").carousel("prev");
            videoContainer.video.pause();
        });
        $(".right").click(function() {
            $("#lightbox").carousel("next");
            videoContainer.video.pause();

        });
        $('#lightbox').on('hidden.bs.modal', function() {
            videoContainer.video.pause();
        })
    }


});