const http = require('http');
const fs = require('fs');
const port = 3000;

const requestHandler = (request, response) => {

    let endpoint = getEndpoint(request);

    if (endpoint) {

        createEndpoint(endpoint);

        if (fs.existsSync(endpoint)) {

            fs.open(endpoint, 'r', () => {

                response.setHeader('Access-Control-Allow-Origin', '*');
                response.setHeader('Access-Control-Allow-Headers', 'origin, content-type, accept');

                response.end(
                    fs.readFileSync(endpoint).toString()
                );

            });

        }


    } else {

        response.writeHeader(200, {"Content-Type": "text/html"});
        response.write('<div style="text-align: center;"><h1>Json server is ready!</h1> <br> <a target="_blank" href="https://github.com/DrobotAlexandr/json-server.js">https://github.com/DrobotAlexandr/json-server.js</a></div>');
        response.end();

    }


    function createEndpoint(endpoint) {

        createEndpointFolders(endpoint);

        function getJson() {
            return '{ "status" : "ok" }';
        }

        function createEndpointFolders(endpoint) {

            let arEndpoint = endpoint.split('/');

            let path = '';

            let i = 0;
            arEndpoint.forEach(folder => {
                i++;

                if (i !== arEndpoint.length) {
                    path += folder + '/';
                } else {
                    path += folder;
                }

                if (folder.indexOf('.') + 1 === 0) {

                    if (!fs.existsSync(endpoint)) {

                        fs.mkdirSync(path, {recursive: true});

                    }

                }
            });

        }

        if (!fs.existsSync(endpoint) && endpoint.indexOf('favicon.ico') + 1 === 0) {

            fs.openSync(endpoint, 'w');

            fs.writeFileSync(endpoint, getJson());

        }


    }

    function getEndpoint(request) {

        if (request.url === '/') {
            return false;
        }

        return __dirname + '' + request.url;
    }
};

const server = http.createServer(requestHandler);

server.listen(port, (err) => {

});
