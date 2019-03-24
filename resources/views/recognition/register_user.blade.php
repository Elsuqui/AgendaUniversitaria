@extends('layouts.app')
@section('css')
    <style type="text/css">
        .face_detector_container {
            border: #0f0f10 groove medium;
        }

        .invisible {
            display: none;
        }

        .counter {
            height: 5em;
            vertical-align: middle;
            text-align: center;
        }

        .element_count {
            padding: 0;
            font-size: 5em;
            vertical-align: middle;
            line-height: 50px;
        }

        .file_input {
            display: none;
            visibility: hidden;
            width: 0;
            height: 0;
        }

    </style>
@endsection
@section('content')
    <div class="ui container" style="padding-top: 10%;">
        <div class="ui equal width center aligned padded grid stackable">
            <div class="row">
                <div class="eight wide column">
                    <div class="ui raised segments">
                        <div class="ui top attached compact segment inverted nightli">
                            <h3 class="ui header">
                                Bievenido al Sistema de Planificacion y Agendamiento Docente
                            </h3>
                            <h2>
                                REGISTRO DE DOCENTE
                            </h2>
                        </div>
                        <div class="ui compact attached segment" id="contendor">
                            <strong id="info"></strong>
                            <video id="face_cam" width="400" height="400" class="invisible"></video>
                            <div class="ui segment">
                                <canvas id="face_detector"
                                        class="face_detector_container" width="400" height="400"></canvas>
                                <div class="ui teal progress" id="recognize_progress" style="display: none;">
                                    <div class="bar">
                                        <div class="progress"></div>
                                    </div>
                                    <div class="label">Detectando Rostro <span id="porcentaje_progress">0</span>%</div>
                                </div>
                            </div>
                            <canvas id="capturedimage" class="invisible" width="400" height="400"></canvas>
                        </div>
                        <div class="ui bottom attached compact segment">
                            <div class="ui message" id="imagen_captura_fallida_container">
                                <strong id="mensaje_captura_fallida"></strong>
                            </div>
                            <div class="counter invisible" id="counter_container">
                                <div class="ui countdown image element_count" id="count_number">3</div>
                            </div>
                            <button class="ui button primary" id="shoot" style="display: none;">Tomar Foto</button>
                        </div>
                    </div>
                </div>
                <div class="four wide column" id="formulario_docente" style="display: none;">
                    <div class="ui segment">
                        <div class="ui form">
                            <div class="ui dividing header">
                                Informacion del docente
                                <img src="{{ asset('imagenes/logo_ucsg.png') }}" class="ui massive image logo"
                                     style="height: 5em; width: 5em;">
                            </div>
                            <div class="field">
                                <label>Apellidos</label>
                                <input type="text" class="ui input" id="apellidos" name="apellidos"
                                       placeholder="Apellidos">
                            </div>
                            <div class="field">
                                <label>Nombres</label>
                                <input type="text" class="ui input" id="nombres" name="nombres" placeholder="Nombres">
                            </div>
                            <div class="field" id="correo_field">
                                <label>Correo</label>
                                <input type="email" class="ui input" id="correo" name="correo" placeholder="Correo">
                            </div>
                            <div class="field">
                                <label>Foto Perfil</label>
                                <input type="file" value="" class="file_input" name="perfil" id="img_perfil_file">
                                <button class="ui basic button" id="upload_img">
                                    <i class="icon photo"></i>
                                    Subir Imagen
                                </button>
                            </div>
                            <div class="field">
                                <img width="100" src="" height="100" class="face_detector_container" id="foto_perfil">
                            </div>
                            <div class="ui error message invisible" id="error_message_container">
                                <p id="error_form" style="color: red;"></p>
                            </div>
                            <div class="ui button" tabindex="0" id="guardarInfomacion">Guardar</div>
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
        let videoWidth, videoHeight;

        let utils = new Utils('errorMessage'); //use utils class

        // whether streaming video from the camera.
        let streaming = false;
        //Recurso de donde se captura el video
        let video = document.getElementById('face_cam');

        let nombres = $('#nombres');
        let apellidos = $('#apellidos');
        let correo = $('#correo');

        let canvasOutput = document.getElementById('face_detector');
        let canvasOutputCtx = canvasOutput.getContext('2d');
        let stream = null;

        let FPS = 30;

        let punto_x_marco_interno = 135;
        let punto_y_marco_interno = 100;
        let ancho_marco_interno;
        let alto_marco_interno;
        let color_marco = 'red';

        let images = null;
        let img_perfil = "";


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


        let count = 3;
        let timer;

        let fotos_tomadas = 0;
        let max_fotos = 1;

        $(document).ready(function () {

            //Se agrega la barra de progreso del reconocimiento facial
            $('#recognize_progress').progress({
                total: 1,
                value: 0,
                text: {
                    active: '{value} de {total}',
                    success: '{total} Fotos Cargadas!'
                }
            });

            $("#shoot").hide();
            $("#imagen_captura_fallida_container").hide();

            $("#shoot").on("click", function () {
                tomarFoto();
            });

            $('#upload_img').click(function () {
                $('#img_perfil_file').click();
            });

            $("#guardarInfomacion").click(function () {
                // console.log(images.length);
                sendInformation();
                /*if(images.length === 4)
                {
                    sendInformation();
                }*/
            });

            $('#img_perfil_file').change(function (e) {
                let file = e.target.files[0];
                let fr = new FileReader();

                let foto = document.getElementById("foto_perfil");
                const elem = document.createElement('canvas');
                elem.width = 400;
                elem.height = 400;
                const ctx = elem.getContext('2d');

                // let imgsize = file.size/1024;
                // console.log(imgsize);

                fr.readAsDataURL(file);

                if (file.type.match('image.*')) {
                    fr.addEventListener("load", function (e) {
                        let img = new Image();
                        img.src = e.target.result;
                        img.onload = function () {
                            ctx.drawImage(img, 0, 0, 400, 400);
                            let base64_fixed = elem.toDataURL('image/jpeg', 1.0);
                            img_perfil = base64_fixed;
                            //console.log(base64_fixed);
                        };

                        foto.src = e.target.result;
                    });
                }
            });

            //timer = setInterval(function() { handleTimer(); }, 1200);

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
            $("#info").text("Esperar a que el marco este de color amarillo y presionar el botón Tomar Foto, luego permanecer durante 3 segundos");
            $('#recognize_progress').css('display', 'block');
            $('#shoot').show();
            setTimeout(processVideo, 0);
            //stopCamera();
        }


        function processVideo() {
            if (fotos_tomadas === max_fotos) {
                stopCamera();
            }
            else {
                let begin = Date.now();
                stats.begin();
                canvasInputCtx.drawImage(video, 0, 0, videoWidth, videoHeight);
                let imageData = canvasInputCtx.getImageData(0, 0, videoWidth, videoHeight);
                srcMat.data.set(imageData.data);
                // Matriz a color en RGB
                let imagen_rgb = srcMat;
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
                        //console.log(region);
                        roi_marco = new cv.Rect(punto_x_marco_interno, punto_y_marco_interno, ancho_marco_interno, alto_marco_interno);
                        //cv.imshow(canvasImageTemp, grayMat.roi(roi_marco));
                        cv.imshow(canvasImageTemp, srcMat.roi(roi_marco));
                    }
                }

                faceMat.delete();
                faceVect.delete();
                stats.end();

                let delay = 1000 / FPS - (Date.now() - begin);
                setTimeout(processVideo, delay);
            }
        }

        function tomarFoto() {
            $("#counter_container").removeClass("invisible");
            $("#imagen_captura_fallida_container").hide();
            timer = setInterval(function () {
                handleTimer();
            }, 1000);
        }

        function handleTimer() {
            if (stop_progress === true) {
                $("#counter_container").addClass("invisible");
                count = 3;
                $("#imagen_captura_fallida_container").show();
                $("#mensaje_captura_fallida").text("Catura Fallida!. Trate de mantenerse en el cuadro mientras este de color amarillo");
                $('#count_number').html(count);
                clearInterval(timer);
            }
            else {
                if (count === 0) {
                    fotos_tomadas++;
                    $("#counter_container").addClass("invisible");
                    $('#recognize_progress').progress('set progress', fotos_tomadas);
                    count = 3;
                    $('#count_number').html(count);
                    clearInterval(timer);
                    savePicture(canvasImageTemp.toDataURL('image/jpeg', 1.0));
                } else {
                    count--;
                    $('#count_number').html(count);
                }
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
            $('#shoot').hide();
            $('#formulario_docente').transition('slide right');
            //showPictures();
            //sendImages();
        }

        function savePicture(imagen) {
            //Tomo la imagen en ese momento y la guardo en el arreglo
            images =  imagen;
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


        function stopVideoProcessing() {
            if (src != null && !src.isDeleted()) src.delete();
            if (dstC1 != null && !dstC1.isDeleted()) dstC1.delete();
            if (dstC3 != null && !dstC3.isDeleted()) dstC3.delete();
            if (dstC4 != null && !dstC4.isDeleted()) dstC4.delete();
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

        function initUI() {
            stats = new Stats();
            stats.showPanel(0);
            document.getElementById('contendor').appendChild(stats.dom);
        }

        function showPictures() {
            //Muestro las imagenes con el fin de probar
            images.forEach((value, key) => {
                $('#imagen' + key).attr('src', value);
                console.log(value.imagen);
            });
        }

        function validarFormulario() {
            return ($.trim(nombres.val()).length > 0 && $.trim(apellidos.val()).length > 0 && $.trim(correo.val()).length > 0 && $.trim(correo.val()).length > 0);
        }

        //Funcion para poder crear la solicutd de inicio de sesion con las fotos que se han obtenido de la deteccion de rostros
        function sendInformation() {
            //Realizo una peticion ajax al servicio de procesamiento de imagen
            console.log(validarFormulario());
            if (validarFormulario() === true) {
                console.log(images);
                let send_object = {
                    "usuario": {
                        "nombres": nombres.val() + " " + apellidos.val(),
                        "correo": correo.val(),
                    },
                    "imagenes": images
                };

                console.log(send_object);
                $.ajax({
                    url: "http://127.0.0.1:5000/register",
                    //url: "https://0bd89f48.ngrok.io/register"
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
                    success: function (response) {
                        console.log("imagenes", response);
                        let objeto_respuesta = response.respuesta;
                        if (objeto_respuesta.error === true) {
                            $("#error_form").text(objeto_respuesta.mensaje);
                            $("#error_message_container").show();
                            $("#correo_field").addClass("error");
                        } else {
                            console.log(objeto_respuesta.usuario);
                            $("#error_message_container").hide();
                            window.location.replace("{{ route('reconocimiento_docente') }}");
                        }
                    },
                });
            }
            else {
                alert("Error en los campos del formulario todos son obligatorios");
            }
        }

    </script>
@endsection
