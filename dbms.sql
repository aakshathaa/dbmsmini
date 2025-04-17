create database eventmanage;
use eventmanage;

CREATE TABLE VENUE (
    V_ID INT PRIMARY KEY,
    VNAME VARCHAR(100),
    ADDR VARCHAR(255),
    VCITY VARCHAR(100)
);

INSERT INTO VENUE (V_ID, VNAME, ADDR, VCITY) VALUES
(1, 'Eden Gardens', '1, BBD Bagh, Kolkata', 'Kolkata'),
(2, 'Wankhede Stadium', 'D Road, Churchgate, Mumbai', 'Mumbai'),
(3, 'Jawaharlal Nehru Stadium', 'Delhi Gate, New Delhi', 'New Delhi'),
(4, 'Sree Kanteerava Stadium', 'Kanteerava Stadium Road, Bengaluru', 'Bengaluru'),
(5, 'Salt Lake Stadium', 'Salt Lake, Sector III, Kolkata', 'Kolkata'),
(6, 'DY Patil Stadium', 'DY Patil University, Navi Mumbai', 'Mumbai'),
(7, 'Jawaharlal Nehru Stadium', 'Vasant Kunj, New Delhi', 'New Delhi'),
(8, 'M.A. Chidambaram Stadium', 'Chepauk, Chennai', 'Chennai'),
(9, 'Rajiv Gandhi International Cricket Stadium', 'Uppal, Hyderabad', 'Hyderabad'),
(10, 'Mysore Palace Grounds', 'Mysuru Palace, Mysuru', 'Mysuru');

select * FROM VENUE;

CREATE TABLE EVENTT (
    E_ID INT PRIMARY KEY,
    E_NAME VARCHAR(100),
    DATE DATE,
    V_ID INT,
    FOREIGN KEY (V_ID) REFERENCES VENUE(V_ID)
);

INSERT INTO EVENTT (E_ID, E_NAME, DATE, V_ID) VALUES
(1, 'IPL Final', '2025-05-01', 1),
(2, 'Pro Kabaddi League', '2025-06-15', 2),
(3, 'Sufi Music Night', '2025-07-10', 3),
(4, 'Bollywood Concert', '2025-08-20', 4),
(5, 'Startup Fest', '2025-09-05', 5),
(6, 'Art Exhibition', '2025-10-25', 6),
(7, 'Stand-Up Comedy Show', '2025-11-10', 7),
(8, 'Classical Dance Performance', '2025-12-05', 8),
(9, 'Carnatic Music Concert', '2026-01-01', 9),
(10, 'Food & Culture Festival', '2026-02-10', 10);

select * FROM EVENTT;

CREATE TABLE TICKET (
    T_ID INT PRIMARY KEY,
    TYPE VARCHAR(50),
    PRICE DECIMAL(10, 2),
    AVAILABILITY INT,
    E_ID INT,
    FOREIGN KEY (E_ID) REFERENCES EVENTT(E_ID)
);

INSERT INTO TICKET (T_ID, TYPE, PRICE, AVAILABILITY, E_ID) VALUES
(1, 'VIP', 3000.00, 1, 1),
(2, 'GENERAL', 1000.00, 0, 1),
(3, 'VIP', 2000.00, 1, 2),
(4, 'GENERAL', 700.00, 1, 2),
(5, 'VIP', 2500.00, 0, 3),
(6, 'GENERAL', 800.00, 1, 3),
(7, 'VIP', 3500.00, 1, 4),
(8, 'GENERAL', 1200.00, 0, 4),
(9, 'VIP', 4000.00, 1, 5),
(10, 'GENERAL', 1500.00, 1, 5);

select * FROM TICKET;

CREATE TABLE ATTENDEES (
    A_ID INT PRIMARY KEY,
    A_NAME VARCHAR(100),
    A_EMAIL VARCHAR(100) UNIQUE,
    A_PHONE VARCHAR(20),
    A_USERNAME VARCHAR(100) UNIQUE,     
    A_PASSWORD VARCHAR(255)              
);

INSERT INTO ATTENDEES (A_ID, A_NAME, A_EMAIL, A_PHONE, A_USERNAME, A_PASSWORD) VALUES
(1, 'Amit Kumar', 'amit.kumar@gmail.com', '9876543210', 'amitkumar', 'ak123456'),
(2, 'Priya Sharma', 'priya.sharma@gmail.com', '8765432109', 'priyasharma', 'ps123456'),
(3, 'Ravi Reddy', 'ravi.reddy@gmail.com', '7654321098', 'ravireddy', 'rr123456'),
(4, 'Anjali Gupta', 'anjali.gupta@gmail.com', '6543210987', 'anjaligupta', 'ag123456'),
(5, 'Suresh Patel', 'suresh.patel@gmail.com', '5432109876', 'sureshpatel', 'sp123456'),
(6, 'Shalini Verma', 'shalini.verma@gmail.com', '4321098765', 'shaliniverma', 'sv123456'),
(7, 'Vikram Singh', 'vikram.singh@gmail.com', '3210987654', 'vikramsingh', 'vs123456'),
(8, 'Pooja Agarwal', 'pooja.agarwal@gmail.com', '2109876543', 'poojaagarwal', 'pa123456'),
(9, 'Manoj Iyer', 'manoj.iyer@gmail.com', '1098765432', 'manojiyer', 'mi123456'),
(10, 'Sneha Menon', 'sneha.menon@gmail.com', '1234567890', 'snehamenon', 'sm123456');

select * FROM ATTENDEES;

CREATE TABLE BUYS (
    A_ID INT,
    T_ID INT,
    PRIMARY KEY (A_ID, T_ID),
    FOREIGN KEY (A_ID) REFERENCES ATTENDEES(A_ID),
    FOREIGN KEY (T_ID) REFERENCES TICKET(T_ID)
);

INSERT INTO BUYS (A_ID, T_ID) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

select * FROM BUYS;


CREATE TABLE PAYMENT (
    P_ID INT PRIMARY KEY,
    PDATE DATE,
    PMETHOD VARCHAR(50),
    A_ID INT,
    FOREIGN KEY (A_ID) REFERENCES ATTENDEES(A_ID)
);

INSERT INTO PAYMENT (P_ID, PDATE, PMETHOD, A_ID) VALUES
(1, '2025-05-01', 'Cash', 1),
(2, '2025-06-15', 'Card', 2),
(3, '2025-07-10', 'UPI', 3),
(4, '2025-08-20', 'Cash', 4),
(5, '2025-09-05', 'Card', 5),
(6, '2025-10-25', 'UPI', 6),
(7, '2025-11-10', 'Cash', 7),
(8, '2025-12-05', 'Card', 8),
(9, '2026-01-01', 'UPI', 9),
(10, '2026-02-10', 'Cash', 10);

select * FROM PAYMENT;