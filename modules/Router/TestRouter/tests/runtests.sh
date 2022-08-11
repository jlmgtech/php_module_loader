#!/bin/bash

php -S 0.0.0.0:8080 test.php &> /dev/null &
serverPID=$!
echo "Server started with PID $serverPID"

sleep 0.5

# run phpunit tests
echo "TODO run phpunit tests here"

# run curl tests
curl "http://localhost:8080/" &> run.log
if [ $? -eq 0 ]; then
    echo "Tests passed"
else
    echo "Tests failed"
    cat run.log
fi

kill $serverPID
