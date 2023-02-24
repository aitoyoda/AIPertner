const express = require('express');
const app = express();
const port = 8080;
const WebSocket = require("ws")
const wss = new WebSocket.Server({ port: 8000 })
const schedule = require('node-schedule')
const request = require("request")
const moment = require('moment')

let job;

let remind_flag = false
let subject_flag = false
let date_flag = false
let subject = ""
let date = ""

function remind_reset() {
    remind_flag = false
    subject_flag = false
    date_flag = false
    subject = ""
    date = ""
}

wss.on("connection", function (ws) {
    ws.on("message", function (message) {
        job = schedule.scheduleJob({
            // hour: 18,
            // minute: 30
            second: parseInt(message)
        }, function () {
            console.log(123456789);
            ws.send("通知111")
        })

    })

    ws.on("createRemind", function (message) {
        request({
            url: "http://localhost/test2/remind.php", method: "POST", form: {
                subject: subject,
                date: date
            }
        }, (err, res, body) => {
            remind_reset()
        })
    })
})

// CORSを有効
app.use(function (req, res, next) {
    res.header('Access-Control-Allow-Origin', '*');
    res.header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept');
    next();
})

app.get('/chat', function (req, res) {
    const userInputText = req.query.text;
    const callback = req.query.callback;
    const response = { output: [] };
    const msg = response.output;
    const str = '';

    if (userInputText == 'リマインド') {
        msg.push({
            type: 'text',
            value: 'リマインドしますか？',
            delayMs: 500 //表示ディレイ（ミリ秒）
        })
        remind_flag = true
        //オプションボタンを作る
        const opts = [];
        opts.push({ label: 'はい', value: 'はい' });
        opts.push({ label: 'いいえ', value: 'いいえ' });
        msg.push({ type: 'option', options: opts });
    } else if (remind_flag && userInputText == 'いいえ') {
        remind_reset()
    } else if (remind_flag && userInputText == 'はい') {
        subject_flag = true
        msg.push({
            type:'text',
            value:'件名を送信してください',
            delayMs: 500
        })
    } else if (remind_flag && subject_flag){
        subject_flag = false
        date_flag = true
        msg.push({
            type: 'text',
            value: 'リマインド時刻を送信してください',
            delayMs: 500           
        })
        subject = userInputText
    } else if (remind_flag && date_flag) {
        date = userInputText
        msg.push({
            type:'text',
            value:'リマインドしました',
            remind: {
                subject: subject,
                date: date,
            },
            delayMs: 500
        })
        request({
            url: "http://localhost/test2/remind.php", method: "POST", form: {
                subject: subject,
                date: date
            }
        }, (err, res, body) => {
            console.log(err)
        })
    
    } else {
        if (remind_flag) {
            if (subject && date) {
                msg.push({
                    type:'text',
                    value:'リマインドしました' ,
                    delayMs: 500
                })
                console.log('post')
                request({ url: "http://localhost/test2/remind.php" , method: "POST", form: {
                    subject: subject,
                    date: date
                }}, (err, res, body) => {
                    console.log(err)
                })
            } else {
                remind_reset()
                msg.push({
                    type:'text',
                    value:'もう一度最初からお願いします',
                    delayMs:500
                })
            }
        } else {
            msg.push({
                type: 'text',
                value: '「' + userInputText + '」ですね！'
            });
        }
}
    if (callback) {
        const responseText = callback + '(' + JSON.stringify(response) + ')';
        res.set('Content-Type', 'application/javascript');
        res.send(responseText);

    } else {
        res.json(response);
    }
});
app.listen(port, () => {
    console.log('サーバを開始しました。ポート番号:' + port);
});



