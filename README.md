# วิธีการติดตั้ง 
1. คัดลอกลิงก์ https://github.com/Korpol54/shorturl.git
2. เลือกที่เก็บโฟลเดอร์ แล้วพิมพ์ cmd หรือ เปิด Command Prompt แล้วใช้คำสั่ง cd ไปที่โฟลเดอร์ที่ต้องการเก็บไฟล์ เช่น cd xampp/htdocs
3. ใช้คำสั่ง git clone ตามด้วยลิงก์ที่คัดลอกมา//  git clone https://github.com/Korpol54/shorturl.git
4. เมื่อ clone เสร็จแล้ว ใช้คำสั่ง cd ไปที่โฟลเดอร์ // cd xampp/htdocs/shorturl
5. ใช้คำสั่ง code .

# วิธีทดสอบระบบ
1. เปิดโปรแกรม xampp แล้วกด Start ที่ Apache และ MySQL
2. กดปุ่ม Admin ของ MySQL เพื่อสร้างฐานข้อมูล
3. กดปุ่ม New ไป ที่ SQL แล้วคัดลอกโค้ดในไฟล์ database.sql มาวางแล้วกด Go
4. เรียกใช้ไฟล์ index.php ด้วยการ copy path ใส่ด้านหลัง http://localhost/'path'
5. ทดสอบระบบ
