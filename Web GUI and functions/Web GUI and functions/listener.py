import serial
import requests

arduino = serial.Serial('COM5', 9600)

while True:
    if arduino.in_waiting:
        data = arduino.readline().decode().strip()

        if data == "TRASH_DETECTED":
            requests.post(
                "http://localhost/cashfortrash/api.php",
                data={"event": "trash"}
            )

        elif data == "COIN_DISPENSED":
            requests.post(
                "http://localhost/cashfortrash/api.php",
                data={"event": "coin"}
            )