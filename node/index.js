var express = require('express');
var app = express();
var bodyParser = require('body-parser');

app.use(bodyParser.json())

var axios = require('axios');
const API_KEY = 'AIzaSyC8UpS3aECUWjniIYvonDRK1HwQAxHqGZA';

app.post('/', function (req, res) {
    var authOptions = {
        method: 'POST',
        url: 'https://fcm.googleapis.com/fcm/send',
        data: (req.body),
        headers: {
            'Authorization': 'key=' + API_KEY,
            'Content-Type': 'application/json'
        },
        json: true
    };
    axios(authOptions)
        .then((resp) => {
            console.log(resp);
        })
        .catch((error) => {
            console.log(error);
        })

    res.send({success: true});
})

var server = app.listen(8081, function () {
    var host = server.address().address
    var port = server.address().port

    console.log("Node app listening at http://%s:%s", host, port)
})