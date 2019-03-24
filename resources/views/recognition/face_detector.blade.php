{{-- *\Pagina para realizar el proceso de deteccion de rostro del docente Univeristario/* --}}
@extends('layouts.app')
@section('css')
    <style type="text/css">
        .face_detector_container {
            border: #0f0f10 groove medium;
        }

        .invisible {
            display: none;
        }
    </style>

    <
@endsection
@section('content')
    <!-- Contenedor de Canvas donde se mostrara la camara -->
    <div class="ui container" style="padding-top: 10%;">
        <form id="form_login" method="POST" action="{{ route("reconocimiento_docente.login") }}" hidden>
            @csrf
            <input type="email" hidden name="email" id="email" value="">
            <input type="text" hidden name="id" id="id" value="">
            <input type="text" hidden name="validatorKey" id="validatorKey" value="">
        </form>
        <div class="ui equal width center aligned padded grid stackable">
            <div class="row">
                <div class="eight wide column">
                    <div class="ui raised segments">
                        <div class="ui top attached compact segment inverted nightli">
                            <h3 class="ui header">
                                Bievenido al Sistema de Planificacion y Agendamiento Docente
                            </h3>
                            <h2>
                                LECTOR FACIAL
                            </h2>
                        </div>
                        <div class="ui compact attached segment" id="contendor">
                            <strong id="info"></strong>
                            <video id="face_cam" width="400" height="400" class="invisible"></video>
                            <div class="ui segment">
                                <div class="ui dimmer" id="dimmer_container">
                                    <div class="ui text loader">Procesando Rostro</div>
                                </div>
                                <canvas id="face_detector"
                                        class="face_detector_container" width="400" height="400"></canvas>
                            </div>
                            <div class="ui teal progress" id="recognize_progress" style="display: none;">
                                <div class="bar">
                                    <div class="progress"></div>
                                </div>
                                <div class="label">Detectando Rostro <span id="porcentaje_progress">0</span>%</div>
                            </div>
                            <canvas id="capturedimage" class="invisible" width="400" height="400"></canvas>
                        </div>
                        <div class="ui bottom attached compact segment">
                            <div class="ui very relaxed horizontal list">
                                <div class="item">
                                    <img class="ui avatar image" src="{{ asset('imagenes/iconos/complete.png') }}">
                                    <div class="content">
                                        <a class="header">Deteccion</a>
                                    </div>
                                </div>
                                <div class="item">
                                    <img class="ui avatar image" src="{{ asset('imagenes/iconos/complete.png') }}">
                                    <div class="content">
                                        <a class="header">Reconocimiento</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(session('error_verificacion') == true)
                            <div class="ui segment">
                                <strong id="info">Fallo en la lectura facial. Docente no registrado, intentelo de nuevo!</strong>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ @asset('js/utils.js') }}" type="text/javascript"></script>
    <script src="{{ @asset('js/opencv.js') }}" type="text/javascript"></script>
    {{--<script src="{{ @asset('js/stats.min.js') }}" type="text/javascript"></script>--}}

    <script type="text/javascript">

        let porcentaje_reconocimiento = 0;

        let utils = new Utils('errorMessage'); //use utils class

        /*let stats = null;*/

        let videoWidth, videoHeight;

        let stop_progress = false;

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

        let images = null;

        let max_time = 100;

        let acumulator_time = 0;

        let current_time = 0;

        let punto_x_marco_interno = 135;
        let punto_y_marco_interno = 100;
        let ancho_marco_interno;
        let alto_marco_interno;
        let color_marco = 'red';


        //Ruta del archivo a donde se va a podir la cascada
        let ruta_archivo_cascada = "{{ @route('archivo_cascada') }}";

        //Nombre del achivo de clasificador tipo cascada que se pedira al servidor
        let nombre_archivo_cascada = 'haarcascade_frontalface_default.xml';

        //Variable del clasificador
        let faceClassifier = null;

        let region = null;

        let src = null;
        let dstC1 = null;
        let dstC3 = null;
        let dstC4 = null;

        let canvasInput = null;
        let canvasInputCtx = null;

        let canvasBuffer = null;
        let canvasBufferCtx = null;

        let canvasImageTemp = null;
        let canvasImageTempCtx = null;

        $(document).ready(() => {

            $('#dimmer_container').dimmer('hide');

            //Se agrega la barra de progreso del reconocimiento facial
            $('#recognize_progress').progress({
                total: 100,
                value: 0,
            });

            faceClassifier = new cv.CascadeClassifier();

            //Se carga el clasificador de cascada
            utils.createFileFromUrl(nombre_archivo_cascada, ruta_archivo_cascada, () => {
                // load pre-trained classifier
                let cargado = faceClassifier.load(nombre_archivo_cascada); // in the callback, load the cascade from file
                console.log(cargado);
                /*initUI();*/
                startCamera();
            });

           //var self = this;

            /*$("#pedirPermiso").on('click', function () {
                startCamera();
                console.log("presionando boton");
            });*/

        });

        function startCamera() {
            if (streaming) return;
            navigator.mediaDevices.getUserMedia({video: true, audio: false})
                .then(function (s) {
                    stream = s;
                    video.srcObject = s;
                    video.play();
                })
                .catch(function (err) {
                    console.log("An error occured! " + err);
                    message.innerHTML = "Permitir el uso de la camara web para poder continuar! ---> ";
                    $(message).css('color', 'red');
                    $('#pedirPermiso').removeClass('invisible');
                });

            video.addEventListener("canplay", function (ev) {
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
            if (!streaming) {
                console.warn("Please startup your webcam");
                return;
            }
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

            canvasImageTemp = document.getElementById('capturedimage');
            canvasImageTempCtx = canvasImageTemp.getContext('2d');

            srcMat = new cv.Mat(videoHeight, videoWidth, cv.CV_8UC4);
            grayMat = new cv.Mat(videoHeight, videoWidth, cv.CV_8UC1);

            //requestAnimationFrame(processVideo);
            $('#recognize_progress').css('display', 'block');
            setTimeout(processVideo, 0);
            //stopCamera();


        }

        function stopVideoProcessing() {
            if (src != null && !src.isDeleted()) src.delete();
            if (dstC1 != null && !dstC1.isDeleted()) dstC1.delete();
            if (dstC3 != null && !dstC3.isDeleted()) dstC3.delete();
            if (dstC4 != null && !dstC4.isDeleted()) dstC4.delete();
        }

        /*function initUI() {
            stats = new Stats();
            stats.showPanel(0);
            document.getElementById('contendor').appendChild(stats.dom);
        }*/


        function processVideo() {
            if (current_time === max_time) {
                stopCamera();
            }
            else {
                let begin = Date.now();
               /* stats.begin();*/
                canvasInputCtx.drawImage(video, 0, 0, videoWidth, videoHeight);
                let imageData = canvasInputCtx.getImageData(0, 0, videoWidth, videoHeight);
                srcMat.data.set(imageData.data);
                cv.cvtColor(srcMat, grayMat, cv.COLOR_RGBA2GRAY);
                let size;
                let faces = [];
                let faceVect = new cv.RectVector();
                let faceMat = new cv.Mat();
                let region = null;

                cv.pyrDown(grayMat, faceMat);
                cv.pyrDown(faceMat, faceMat);
                size = faceMat.size();

                faceClassifier.detectMultiScale(faceMat, faceVect);

                for (let i = 0; i < faceVect.size(); i++) {
                    let face = faceVect.get(i);
                    region = new cv.Rect(face.x, face.y, face.width, face.height);
                    faces.push(region);
                }

                canvasOutputCtx.drawImage(canvasInput, 0, 0, videoWidth, videoHeight);
                drawResults(canvasOutputCtx, faces, 'red', size);

                if (region !== null) {
                    if (stop_progress === false) {
                        if (acumulator_time === 100) {
                            console.log(region);
                            roi_marco = new cv.Rect(punto_x_marco_interno, punto_y_marco_interno, ancho_marco_interno, alto_marco_interno);
                            //cv.imshow(canvasImageTemp, grayMat.roi(roi_marco));
                            cv.imshow(canvasImageTemp, srcMat.roi(roi_marco));
                            current_time = acumulator_time;
                            acumulator_time = 0;
                            savePicture(canvasImageTemp.toDataURL('image/jpeg', 1.0));
                        }

                        acumulator_time++;

                        porcentaje_reconocimiento++;
                        $('#recognize_progress').progress('increment');
                        porcentaje_reconocimiento = porcentaje_reconocimiento === 101 ? porcentaje_reconocimiento - 1 : porcentaje_reconocimiento;
                        $('#porcentaje_progress').text(porcentaje_reconocimiento);
                    }
                }


                faceMat.delete();
                faceVect.delete();
                /*stats.end();*/

                let delay = 1000 / FPS - (Date.now() - begin);
                setTimeout(processVideo, delay);
            }
        }

        function drawResults(ctx, results, color, size) {
            drawDetectorContainer();
            for (let i = 0; i < results.length; ++i) {
                let rect = results[i];
                let xRatio = videoWidth / size.width;
                let yRatio = videoHeight / size.height;
                ctx.lineWidth = 3;
                ctx.strokeStyle = color;
                ctx.strokeRect(rect.x * xRatio, rect.y * yRatio, rect.width * xRatio, rect.height * yRatio);
                validateFaceDetectionContainer(rect.x * xRatio, (rect.x * xRatio) + (rect.width * xRatio), rect.y * yRatio, (rect.y * yRatio) + (rect.height * yRatio));
            }
        }

        function drawDetectorContainer() {
            //Capturo la imagen de salida, que es el contenedor
            //Creo una matriz que hace de mascara para poder dibujarla en el video
            ancho_marco_interno = srcMat.cols - (2 * punto_x_marco_interno);
            alto_marco_interno = srcMat.rows - (2 * punto_y_marco_interno);
            canvasOutputCtx.lineWidth = 3;
            canvasOutputCtx.strokeStyle = color_marco;
            canvasOutputCtx.strokeRect(punto_x_marco_interno, punto_y_marco_interno, ancho_marco_interno, alto_marco_interno);
        }

        function validateFaceDetectionContainer(xf1, xf2, yf1, yf2) {
            if (xf1 < punto_x_marco_interno ||
                xf2 > (punto_x_marco_interno + ancho_marco_interno) ||
                yf1 < punto_y_marco_interno || yf2 > (punto_y_marco_interno + alto_marco_interno)) {
                color_marco = 'red';
                stop_progress = true;
            } else {
                color_marco = 'yellow';
                stop_progress = false;
            }
        }

        function stopCamera() {
            if (!streaming) return;
            stopVideoProcessing();
            document.getElementById("face_detector").getContext("2d").clearRect(0, 0, videoWidth, videoHeight);
            video.pause();
            video.srcObject = null;
            stream.getVideoTracks()[0].stop();
            streaming = false;
            $('#dimmer_container').dimmer('show');
            showPictures();
            sendImages();
        }

        function savePicture(imagen) {
            //Tomo la imagen en ese momento y la guardo en el arreglo
            images = imagen;
        }

        function showPictures() {
            // //Muestro las imagenes con el fin de probar
            // images.forEach((value, key) => {
            //     $('#imagen' + key).attr('src', value);
            //     console.log(value.imagen);
            // });
            console.log(images);
        }

        //Funcion para poder crear la solicutd de inicio de sesion con las fotos que se han obtenido de la deteccion de rostros
        function sendImages() {
            //Realizo una peticion ajax al servicio de procesamiento de imagen
            let send_object = {
              "imagenes": images
            };

            console.log(send_object);
            $.ajax({
                //url: "https://0bd89f48.ngrok.io/imagesprocess",
                url: "http://127.0.0.1:5000/imagesprocess",
                //url: "http://192.188.52.222:5000/imagesprocess",
                //url: "https://192.168.0.108:5000/imagesprocess",
                type: "POST",
                contentType: "application/json;charset=utf-8",
                datatype: "json",
                data: JSON.stringify(send_object),
                //crossDomain: true,
                crossOrigin: true,
                headers: {
                    "Access-Control-Allow-Origin": "*",
                    "Access-Control-Allow-Headers": "Content-Type",
                    "Access-Control-Allow-Methods": "POST, GET, OPTIONS"
                },
                success: function(response){
                    console.log("imagenes", response);
                    //Se realiza el submit del form
                    $("#email").val(response.email);
                    $("#id").val(response.id);
                    $("#validatorKey").val(response.validatorKey);
                    $("#form_login").submit();
                },
            });
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


