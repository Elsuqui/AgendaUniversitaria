{{-- *\Pagina para realizar el proceso de deteccion de rostro del docente Univeristario/* --}}
@extends('layouts.app')
@section('css')
    <style type="text/css">
        .face_detector_container{
            border: #0f0f10 groove medium;
        }
    </style>
@endsection
@section('content')
    <!-- Contenedor de Canvas donde se mostrara la camara -->
    <div class="ui container" style="padding-top: 10%;">
        <div class="ui equal width center aligned padded grid stackable">
            <div class="row">
                <div class="eight wide column">
                    <div class="ui raised segments">
                        <div class="ui segment inverted nightli">
                            <h3 class="ui header">
                                Bievenido al Sistema de Planificacion y Agendamiento Docente
                            </h3>
                            <h1>
                                LECTOR FACIAL
                            </h1>
                        </div>
                        {{--<div class="ui segment">
                            <div class="ui image">
                                <img id="image_presentation" src="" alt="Image">
                            </div>
                            <input type="file" id="input_image" name="image">
                        </div>--}}
                        <div class="ui segment" id="contendor">
                            <video id="face_cam" width="400" height="400" style="display: none;"></video>
                            <canvas id="face_detector"
                                    class="face_detector_container" width="400" height="400"></canvas>
                            <div id="capturedimage"></div>
                        </div>
                        <div class="ui segment">
                            <strong id="info"></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ @asset('js/opencv.js') }}" type="text/javascript"></script>
    <script src="{{ @asset('js/utils.js') }}" type="text/javascript"></script>
    <script src="{{ @asset('js/stats.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">

        let utils = new Utils('errorMessage'); //use utils class

        let stats = null;

        let videoWidth, videoHeight;

        // whether streaming video from the camera.
        let streaming = false;
        //Recurso de donde se captura el video
        let video = document.getElementById('face_cam');
        let message = document.getElementById('info');
        //Salida de la deteccion facial
        let canvasOutput = document.getElementById('face_detector');
        let canvasOutputCtx = canvasOutput.getContext('2d');
        let stream = null;

        let FPS = 30;

        let images = [];

        let count_down = 5;


        //Ruta del archivo a donde se va a podir la cascada
        let ruta_archivo_cascada = "{{ @route('archivo_cascada') }}";

        //Nombre del achivo de clasificador tipo cascada que se pedira al servidor
        let nombre_archivo_cascada = 'haarcascade_frontalface_default.xml';

        //Variable del clasificador
        let faceClassifier = null;

        let src = null;
        let dstC1 = null;
        let dstC3 = null;
        let dstC4 = null;

        let canvasInput = null;
        let canvasInputCtx = null;

        let canvasBuffer = null;
        let canvasBufferCtx = null;

        $(document).ready(() => {

            faceClassifier = new cv.CascadeClassifier();

            //Se carga el clasificador de cascada
            utils.createFileFromUrl(nombre_archivo_cascada, ruta_archivo_cascada, () => {
                // load pre-trained classifiers
                let cargado = faceClassifier.load(nombre_archivo_cascada); // in the callback, load the cascade from file
                console.log(cargado);
            });
            initUI();
            startCamera();
        });

        function startCamera() {
            if (streaming) return;
            navigator.mediaDevices.getUserMedia({video: true, audio: false})
                .then(function(s) {
                    stream = s;
                    video.srcObject = s;
                    video.play();
                })
                .catch(function(err) {
                    console.log("An error occured! " + err);
                    message.innerText = "Permitir el uso de la camara web para poder continuar!";
                });

            video.addEventListener("canplay", function(ev){
                if (!streaming) {
                    videoWidth = video.width;
                    videoHeight = video.height;
                    video.setAttribute("width", videoWidth);
                    video.setAttribute("height", videoHeight);
                    canvasOutput.width = videoWidth;
                    canvasOutput.height = videoHeight;
                    streaming = true;
                }
                startVideoProcessing();
            }, false);
        }


        function startVideoProcessing() {
            if (!streaming) { console.warn("Please startup your webcam"); return; }
            //Reiniciar todas las banderas detectores, clasificadores y streams
            stopVideoProcessing();

            canvasInput = document.createElement('canvas');
            canvasInput.width = videoWidth;
            canvasInput.height = videoHeight;
            canvasInputCtx = canvasInput.getContext('2d');

            canvasBuffer = document.createElement('canvas');
            canvasBuffer.width = videoWidth;
            canvasBuffer.height = videoHeight;
            canvasBufferCtx = canvasBuffer.getContext('2d');

            srcMat = new cv.Mat(videoHeight, videoWidth, cv.CV_8UC4);
            grayMat = new cv.Mat(videoHeight, videoWidth, cv.CV_8UC1);

            //requestAnimationFrame(processVideo);
            setTimeout(processVideo, 0);
        }

        function stopVideoProcessing() {
            if (src != null && !src.isDeleted()) src.delete();
            if (dstC1 != null && !dstC1.isDeleted()) dstC1.delete();
            if (dstC3 != null && !dstC3.isDeleted()) dstC3.delete();
            if (dstC4 != null && !dstC4.isDeleted()) dstC4.delete();
        }

        function initUI() {
            stats = new Stats();
            stats.showPanel(0);
            document.getElementById('contendor').appendChild(stats.dom);
        }


        function processVideo() {
            if(count_down === 0)
            {
                stopCamera();
            }else {
                let begin = Date.now();
                stats.begin();
                canvasInputCtx.drawImage(video, 0, 0, videoWidth, videoHeight);
                let imageData = canvasInputCtx.getImageData(0, 0, videoWidth, videoHeight);
                srcMat.data.set(imageData.data);
                cv.cvtColor(srcMat, grayMat, cv.COLOR_RGBA2GRAY);
                let size;
                let faces = [];
                let faceVect = new cv.RectVector();
                let faceMat = new cv.Mat();

                cv.pyrDown(grayMat, faceMat);
                cv.pyrDown(faceMat, faceMat);
                size = faceMat.size();

                faceClassifier.detectMultiScale(faceMat, faceVect);
                console.log(faceMat);

                for (let i = 0; i < faceVect.size(); i++) {
                    let face = faceVect.get(i);
                    region = new cv.Rect(face.x, face.y, face.width, face.height);
                    savePicture(imageData.);
                    faces.push(new cv.Rect(face.x, face.y, face.width, face.height));
                }

                faceMat.delete();
                faceVect.delete();
                canvasOutputCtx.drawImage(canvasInput, 0, 0, videoWidth, videoHeight);
                drawResults(canvasOutputCtx, faces, 'red', size);
                stats.end();

                count_down--;
                let delay = 1000 / FPS - (Date.now() - begin);
                setTimeout(processVideo, delay);
                //requestAnimationFrame(processVideo);
            }
        }

        function drawResults(ctx, results, color, size) {
            for (let i = 0; i < results.length; ++i) {
                let rect = results[i];
                let xRatio = videoWidth/size.width;
                let yRatio = videoHeight/size.height;
                ctx.lineWidth = 3;
                ctx.strokeStyle = color;
                ctx.strokeRect(rect.x*xRatio, rect.y*yRatio, rect.width*xRatio, rect.height*yRatio);
            }
        }

        function stopCamera() {
            if (!streaming) return;
            stopVideoProcessing();
            document.getElementById("face_detector").getContext("2d").clearRect(0, 0, videoWidth, videoHeight);
            video.pause();
            video.srcObject=null;
            stream.getVideoTracks()[0].stop();
            streaming = false;
            console.log(images);
        }

        function savePicture(imagen)
        {
            //Tomo la imagen en ese momento y la guardo en el arreglo
            images.push(imagen);
        }

    </script>

    {{--<script type="text/javascript">
        $(document).ready(function () {

            console.log("Belleza Funciona....!!");
            let utils = new Utils('errorMessage'); //use utils class

            let ruta_archivo_cascada = "{{ @route('archivo_cascada') }}";
            let nombre_archivo_cascada = "haarcascade_frontalface_default.xml";
            console.log(ruta_archivo_cascada);

            utils.createFileFromUrl(nombre_archivo_cascada, ruta_archivo_cascada, () => {
                // load pre-trained classifiers
                let cargado = classifier.load(nombre_archivo_cascada); // in the callback, load the cascade from file
                console.log(cargado);
            });


            let video = document.getElementById("face_cam"); // video is the id of video tag
            let streaming = false;
            let imagen_capturada = null;
            let imagen_transformada = null;

            //Configuracion iniciales para la captura de video en la web
            let gray = new cv.Mat();
            let cap = new cv.VideoCapture(video);
            let faces = new cv.RectVector();
            let classifier = new cv.CascadeClassifier();


            const FPS = 30;

            navigator.mediaDevices.getUserMedia({video: true, audio: false})
                .then((stream) => {
                    video.srcObject = stream;
                    video.play();
                    streaming = true;

                    video.addEventListener("loadstart", () => {
                        imagen_capturada = new cv.Mat(video.height, video.width, cv.CV_8UC4);
                        imagen_transformada = new cv.Mat(video.height, video.width, cv.CV_8UC1);

                        setTimeout(process_video, 0);
                    });
                })
                .catch(function (err) {
                    console.log("An error occured! " + err);
                });

            // schedule first one.


            function process_video() {
                try {
                    if (!streaming) {
                        // clean and stop.
                        imagen_capturada.delete();
                        imagen_transformada.delete();
                        gray.delete();
                        faces.delete();
                        classifier.delete();
                        return;
                    }

                    let begin = Date.now();
                    cap.read(imagen_capturada);
                    imagen_capturada.copyTo(imagen_transformada);
                    cv.cvtColor(imagen_transformada, gray, cv.COLOR_RGBA2GRAY);
                    // detect face.
                    classifier.detectMultiScale(gray, faces, 3, 0);
                    for (let i = 0; i < faces.size(); ++i) {
                        let face = faces.get(i);
                        let punto1 = new cv.Point(face.x, face.y);
                        let punto2 = new cv.Point(face.x + face.width, face.y + face.height);
                        cv.rectangle(imagen_transformada, punto1, punto2, [255, 0, 0, 255]);
                    }
                    cv.imshow("face_detector", imagen_transformada);
                    // schedule next one.
                    let delay = 1000 / FPS - (Date.now() - begin);
                    setTimeout(process_video, delay);

                } catch (err) {
                    console.log("error al realizar el stream", err);
                }
            }


        });

    </script>--}}
@endsection


