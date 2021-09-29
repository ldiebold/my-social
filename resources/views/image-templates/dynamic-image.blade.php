@extends('image-templates.quasar-layout')

@section('content')
    @verbatim
        <div style="height: 100vh;" class="column items-center">
            <q-toolbar class="self-start" style="height: 200px; background-color: #00B4FF;">
                <div class="text-white full-width text-center" style="font-size: 120px;">Episode #{{ episodeNumber }}</div>
                <svg class="q-icon absolute-right text-white" role="presentation" viewBox="0 0 24 24"
                    style="font-size: 200px; right: 20px;">
                    <path
                        d="M17,18.25V21.5H7V18.25C7,16.87 9.24,15.75 12,15.75C14.76,15.75 17,16.87 17,18.25M12,5.5A6.5,6.5 0 0,1 18.5,12C18.5,13.25 18.15,14.42 17.54,15.41L16,14.04C16.32,13.43 16.5,12.73 16.5,12C16.5,9.5 14.5,7.5 12,7.5C9.5,7.5 7.5,9.5 7.5,12C7.5,12.73 7.68,13.43 8,14.04L6.46,15.41C5.85,14.42 5.5,13.25 5.5,12A6.5,6.5 0 0,1 12,5.5M12,1.5A10.5,10.5 0 0,1 22.5,12C22.5,14.28 21.77,16.39 20.54,18.11L19.04,16.76C19.96,15.4 20.5,13.76 20.5,12A8.5,8.5 0 0,0 12,3.5A8.5,8.5 0 0,0 3.5,12C3.5,13.76 4.04,15.4 4.96,16.76L3.46,18.11C2.23,16.39 1.5,14.28 1.5,12A10.5,10.5 0 0,1 12,1.5M12,9.5A2.5,2.5 0 0,1 14.5,12A2.5,2.5 0 0,1 12,14.5A2.5,2.5 0 0,1 9.5,12A2.5,2.5 0 0,1 12,9.5Z" />
                </svg>
            </q-toolbar>
            <div class="col full-width relative-position" style="background-color: #050A14">
                <div class="full-height absolute-top" style="background-image: url(/images/stars.svg);">
                </div>
                <div class="full-height full-width"
                    :style="`border-radius: 32px; font-size: ${fontSize}; padding: calc(2.9vw);`">
                    <div class="full-height text-center full-width text-white flex flex-center">
                        {{ description }}
                    </div>
                </div>
            </div>
        </div>
    @endverbatim
@endsection
