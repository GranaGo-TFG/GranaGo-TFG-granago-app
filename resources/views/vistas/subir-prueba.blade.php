@extends('layouts.app')

@push('styles')
<style>
    .upload-dropbox {
        position: relative;
        display: grid;
        gap: 0.9rem;
        justify-items: center;
        min-height: 18rem;
        padding: 1.75rem;
        border: 2px dashed rgba(217, 28, 74, 0.32);
        border-radius: 1.25rem;
        background:
            radial-gradient(circle at top right, rgba(245, 158, 11, 0.16), transparent 12rem),
            rgba(217, 28, 74, 0.05);
        text-align: center;
        transition: border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }

    .upload-dropbox.is-dragover {
        border-color: rgba(217, 28, 74, 0.8);
        box-shadow: 0 18px 40px rgba(217, 28, 74, 0.12);
        transform: translateY(-0.15rem);
    }

    .upload-dropbox.is-ready {
        border-style: solid;
        border-color: rgba(16, 185, 129, 0.45);
        background:
            radial-gradient(circle at top right, rgba(16, 185, 129, 0.12), transparent 12rem),
            rgba(16, 185, 129, 0.06);
    }

    .upload-dropbox strong {
        font-size: 1.2rem;
    }

    .upload-dropbox p,
    .upload-dropbox span,
    .upload-dropbox small {
        margin: 0;
        color: var(--granago-text-soft);
    }

    .upload-dropbox-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.75rem;
    }

    .upload-dropbox-input {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .upload-preview {
        width: min(100%, 18rem);
        border-radius: 1rem;
        object-fit: cover;
        box-shadow: 0 16px 34px rgba(30, 41, 59, 0.14);
    }

    .upload-status-panel {
        display: grid;
        gap: 1rem;
    }

    .upload-status-list {
        display: grid;
        gap: 0.85rem;
    }

    .upload-status-item {
        display: grid;
        gap: 0.2rem;
    }

    .subir-prueba-layout {
        gap: 4rem !important;
        align-items: start;
    }
</style>
@endpush

@section('content')
<div class="screen-page">
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="detail-layout subir-prueba-layout">
            <div class="home-panel detail-main" style="margin-bottom: 2.5rem;">
                <h1 class="home-kicker">Validacion</h1>
                <h2>Subir prueba del reto</h2>
                <p class="detail-copy">
                    Sube una imagen del reto <strong>{{ $reto->nombre }}</strong>. Puedes arrastrar una imagen o una carpeta:
                    si sueltas una carpeta, se tomara la primera imagen valida encontrada.
                </p>

                <form
                    method="POST"
                    action="{{ route('vistas.subir-prueba.store', $reto) }}"
                    enctype="multipart/form-data"
                    class="d-grid gap-3 mt-4"
                    id="validacion-form"
                >
                    @csrf

                    <label class="upload-dropbox" for="foto_prueba" id="upload-dropbox">
                        <input
                            id="foto_prueba"
                            name="foto_prueba"
                            type="file"
                            accept="image/png,image/jpeg,image/webp"
                            class="upload-dropbox-input"
                            required
                        >
                        <input
                            id="foto_prueba_carpeta"
                            type="file"
                            accept="image/png,image/jpeg,image/webp"
                            class="upload-dropbox-input"
                            webkitdirectory
                            directory
                            multiple
                        >

                        <span>Prueba fotografica</span>
                        <strong>Arrastra aqui una imagen o una carpeta</strong>
                        <p id="upload-help">Tambien puedes seleccionar manualmente un archivo o una carpeta local.</p>
                        <small>Formatos permitidos: JPG, JPEG, PNG y WEBP. Tamano maximo: 5 MB.</small>

                        <div class="upload-dropbox-actions">
                            <button type="button" class="btn btn-primary home-btn" id="pick-image">
                                Elegir imagen
                            </button>
                            <button type="button" class="btn btn-outline-secondary home-btn" id="pick-folder">
                                Elegir carpeta
                            </button>
                        </div>

                        <img
                            id="upload-preview"
                            class="upload-preview d-none"
                            alt="Vista previa de la imagen seleccionada"
                        >
                    </label>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary home-btn">Guardar prueba</button>
                        <a href="{{ route('vistas.reto-detalle', $reto) }}" class="btn btn-outline-secondary home-btn">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>

            <div class="home-panel upload-status-panel" style="margin-top: 2.5rem;">
                <span class="home-kicker">Estado</span>
                <h2>Pendiente de revisar</h2>
                <p class="muted-copy">Cuando la prueba sea aceptada, los puntos del reto se sumaran a tu perfil.</p>
                <div class="upload-status-list">
                    <div class="upload-status-item">
                        <strong>Reto</strong>
                        <span>{{ $reto->nombre }}</span>
                    </div>
                    <div class="upload-status-item">
                        <strong>Zona</strong>
                        <span>{{ $reto->ubicacion_referencia ?? 'Sin zona definida' }}</span>
                    </div>
                    <div class="upload-status-item">
                        <strong>Recompensa</strong>
                        <span>{{ $reto->puntos_recompensa }} puntos</span>
                    </div>
                    <div class="upload-status-item">
                        <strong>Formato</strong>
                        <span>1 imagen por envio</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var dropbox = document.getElementById('upload-dropbox');
    var imageInput = document.getElementById('foto_prueba');
    var folderInput = document.getElementById('foto_prueba_carpeta');
    var helpText = document.getElementById('upload-help');
    var preview = document.getElementById('upload-preview');
    var pickImageButton = document.getElementById('pick-image');
    var pickFolderButton = document.getElementById('pick-folder');

    if (!dropbox || !imageInput || !folderInput || !helpText || !preview) {
        return;
    }

    var imagePattern = /^image\/(jpeg|png|webp)$/i;

    var setFile = function (file) {
        var transfer = new DataTransfer();
        transfer.items.add(file);
        imageInput.files = transfer.files;

        helpText.textContent = 'Seleccionada: ' + file.name;
        dropbox.classList.add('is-ready');

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    };

    var updateFromFileList = function (files) {
        var imageFile = Array.from(files || []).find(function (file) {
            return imagePattern.test(file.type);
        });

        if (!imageFile) {
            helpText.textContent = 'No se encontro ninguna imagen valida. Usa JPG, JPEG, PNG o WEBP.';
            dropbox.classList.remove('is-ready');
            preview.classList.add('d-none');
            preview.removeAttribute('src');
            imageInput.value = '';
            return;
        }

        setFile(imageFile);
    };

    var readEntry = function (entry) {
        return new Promise(function (resolve) {
            if (entry.isFile) {
                entry.file(function (file) {
                    resolve([file]);
                }, function () {
                    resolve([]);
                });
                return;
            }

            if (!entry.isDirectory) {
                resolve([]);
                return;
            }

            var reader = entry.createReader();
            var entries = [];

            var readBatch = function () {
                reader.readEntries(function (batch) {
                    if (!batch.length) {
                        Promise.all(entries.map(readEntry)).then(function (nestedResults) {
                            resolve(nestedResults.flat());
                        });
                        return;
                    }

                    entries = entries.concat(Array.from(batch));
                    readBatch();
                }, function () {
                    resolve([]);
                });
            };

            readBatch();
        });
    };

    var readDropItems = async function (items) {
        var entries = Array.from(items || [])
            .map(function (item) {
                return typeof item.webkitGetAsEntry === 'function' ? item.webkitGetAsEntry() : null;
            })
            .filter(Boolean);

        if (!entries.length) {
            return [];
        }

        var nestedResults = await Promise.all(entries.map(readEntry));
        return nestedResults.flat();
    };

    ['dragenter', 'dragover'].forEach(function (eventName) {
        dropbox.addEventListener(eventName, function (event) {
            event.preventDefault();
            dropbox.classList.add('is-dragover');
        });
    });

    ['dragleave', 'dragend', 'drop'].forEach(function (eventName) {
        dropbox.addEventListener(eventName, function (event) {
            event.preventDefault();
            dropbox.classList.remove('is-dragover');
        });
    });

    dropbox.addEventListener('drop', async function (event) {
        var files = [];

        if (event.dataTransfer && event.dataTransfer.items && event.dataTransfer.items.length) {
            files = await readDropItems(event.dataTransfer.items);
        }

        if (!files.length && event.dataTransfer && event.dataTransfer.files) {
            files = Array.from(event.dataTransfer.files);
        }

        updateFromFileList(files);
    });

    imageInput.addEventListener('change', function () {
        updateFromFileList(imageInput.files);
    });

    folderInput.addEventListener('change', function () {
        updateFromFileList(folderInput.files);
    });

    pickImageButton.addEventListener('click', function (event) {
        event.preventDefault();
        imageInput.click();
    });

    pickFolderButton.addEventListener('click', function (event) {
        event.preventDefault();
        folderInput.click();
    });
});
</script>
@endpush
