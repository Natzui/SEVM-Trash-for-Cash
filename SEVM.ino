#include <Servo.h>

// -------- 7 Segment Pins --------
int a = 2, b = 3, c = 4, d = 5, e = 6, f = 8, g = 9;

// -------- Devices --------
int irPin = 10;
int servoPin = 11;
int buzzerPin = 12;

Servo myServo;

int number = 1;
bool lastState = HIGH;

// ⭐ ADDED (IR delay timer)
unsigned long lastDetectTime = 0;
const unsigned long detectDelay = 1000; // 1 second delay

// --------------------------------
void setup() {

  pinMode(a, OUTPUT); pinMode(b, OUTPUT); pinMode(c, OUTPUT);
  pinMode(d, OUTPUT); pinMode(e, OUTPUT); pinMode(f, OUTPUT);
  pinMode(g, OUTPUT);

  pinMode(irPin, INPUT);
  pinMode(buzzerPin, OUTPUT);

  myServo.attach(servoPin);
  myServo.write(90);   // STOP at start

  showNumber(number);
}

// --------------------------------
void loop() {

  bool irState = digitalRead(irPin);

  // ✅ BUZZER: ON when IR detects, OFF when no detect
  if (irState == LOW) {
    tone(buzzerPin, 5000);
  } else {
    noTone(buzzerPin);
  }

  // ✅ COUNT ONLY ON NEW DETECTION + 1 SECOND DELAY ⭐
  if (irState == LOW && lastState == HIGH &&
      millis() - lastDetectTime >= detectDelay) {

    lastDetectTime = millis();   // ⭐ reset timer

    number++;
    if (number > 9) number = 1;

    showNumber(number);

    // ✅ SERVO SPIN LEFT WHEN COUNT = 9
    if (number == 9) {

      myServo.write(50);
      delay(1000);
      myServo.write(90);
    }

    delay(300); // anti double detect
  }

  lastState = irState;
}

// --------------------------------
// 7 SEGMENT DISPLAY (1–9)
void showNumber(int n) {

  digitalWrite(a, LOW); digitalWrite(b, LOW); digitalWrite(c, LOW);
  digitalWrite(d, LOW); digitalWrite(e, LOW); digitalWrite(f, LOW);
  digitalWrite(g, LOW);

  switch 👎 {

    case 1: digitalWrite(g,HIGH); digitalWrite(e,HIGH); break;

    case 2: digitalWrite(f,HIGH); digitalWrite(g,HIGH);
            digitalWrite(b,HIGH); digitalWrite(c,HIGH);
            digitalWrite(d,HIGH); break;

    case 3: digitalWrite(f,HIGH); digitalWrite(b,HIGH);
            digitalWrite(g,HIGH); digitalWrite(e,HIGH);
            digitalWrite(d,HIGH); break;

    case 4: digitalWrite(a,HIGH); digitalWrite(g,HIGH);
            digitalWrite(b,HIGH); digitalWrite(e,HIGH); break;

    case 5: digitalWrite(a,HIGH); digitalWrite(f,HIGH);
            digitalWrite(e,HIGH); digitalWrite(b,HIGH);
            digitalWrite(d,HIGH); break;

    case 6: digitalWrite(a,HIGH); digitalWrite(f,HIGH);
            digitalWrite(b,HIGH); digitalWrite(e,HIGH);
            digitalWrite(d,HIGH); digitalWrite(c,HIGH); break;

    case 7: digitalWrite(f,HIGH); digitalWrite(g,HIGH);
            digitalWrite(e,HIGH); break;

    case 8: digitalWrite(a,HIGH); digitalWrite(b,HIGH);
            digitalWrite(c,HIGH); digitalWrite(d,HIGH);
            digitalWrite(e,HIGH); digitalWrite(f,HIGH);
            digitalWrite(g,HIGH); break;

    case 9: digitalWrite(a,HIGH); digitalWrite(b,HIGH);
            digitalWrite(e,HIGH); digitalWrite(d,HIGH);
            digitalWrite(f,HIGH); digitalWrite(g,HIGH); break;
  }
}
