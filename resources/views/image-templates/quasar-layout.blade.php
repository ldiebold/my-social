<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet"
        type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/quasar@2.0.4/dist/quasar.prod.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="q-app">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quasar@2.0.4/dist/quasar.umd.prod.js"></script>

    <script>
        const app = Vue.createApp({
            data() {
                const urlSearchParams = new URLSearchParams(window.location.search);
                const params = Object.fromEntries(urlSearchParams.entries());

                console.log('params', params)

                return {
                    description: params.description,
                    episodeNumber: params.episode
                }
            },

            computed: {
                textLength() {
                    return this.description.length
                },

                fontSize() {
                    if (this.description.length < 15) {
                        return '13vw'
                    }

                    if (this.description.length < 30) {
                        return '10vw'
                    }

                    if (this.description.length < 60) {
                        return '8vw'
                    }

                    return '6vw'
                }
            }
        })

        app.use(Quasar)
        app.mount('#q-app')
    </script>
</body>

</html>
