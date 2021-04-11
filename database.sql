CREATE TABLE `address` (
  `Number` int(11) NOT NULL,
  `City` varchar(255) NOT NULL,
  `Street` varchar(255) NOT NULL,
  `Home Number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `address`
  ADD PRIMARY KEY (`Number`);

CREATE TABLE `apply for visa` (
  `Number` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Card Name` varchar(50) NOT NULL,
  `Gender` enum('Male','Female') NOT NULL,
  `Date Of Birth` varchar(10) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Phone Number` varchar(11) NOT NULL,
  `Address` int(11) NOT NULL,
  `Dual Citizenship` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `apply for visa`
  ADD PRIMARY KEY (`Number`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Address` (`Address`);

CREATE TABLE `bank account` (
  `Account Number` int(11) NOT NULL,
  `Type` enum('Storage','Benefits Paid','Benefits Received','Profits','Loss','Incoming') NOT NULL,
  `Money` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `bank account`
  ADD PRIMARY KEY (`Account Number`),
  ADD UNIQUE KEY `Type` (`Type`);

CREATE TABLE `benefits paid` (
  `Number` int(11) NOT NULL,
  `Account Number` int(11) NOT NULL,
  `Date` varchar(6) NOT NULL,
  `Delivered Date` int(11) NOT NULL,
  `Benefits` double NOT NULL,
  `Delivered` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `benefits paid`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `benefits paid_ibfk_1` (`Account Number`);

CREATE TABLE `benefits received` (
  `Number` int(11) NOT NULL,
  `Account Number` int(11) NOT NULL,
  `Date` varchar(4) NOT NULL,
  `Benefits` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `benefits received`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `Account Number` (`Account Number`);

CREATE TABLE `benefits transactions` (
  `Number` int(11) NOT NULL,
  `Date` varchar(11) NOT NULL,
  `Account Number` int(11) NOT NULL,
  `Account Type` enum('Saving','Loan') NOT NULL,
  `Benefits Type` enum('Paid','Received') NOT NULL,
  `Benefits` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `benefits transactions`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `Account Number` (`Account Number`);

CREATE TABLE `client` (
  `Account Number` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Age` int(11) NOT NULL,
  `National ID` int(11) NOT NULL,
  `Address` int(11) NOT NULL,
  `Phone Number` varchar(11) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Gender` enum('Male','Female','None') NOT NULL,
  `Account Type` set('Saving Account','Current Account','Loan', 'None') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `client`
  ADD PRIMARY KEY (`Account Number`),
  ADD UNIQUE KEY `National ID` (`National ID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Address` (`Address`);

CREATE TABLE `clientlogin` (
  `ID` int(11) NOT NULL,
  `Username` varchar(40) NOT NULL,
  `Email` varchar(40) DEFAULT NULL,
  `Password` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `clientlogin`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

CREATE TABLE `comment` (
  `Number` int(11) NOT NULL,
  `Date` varchar(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Department` enum('Manager','Accounting','IT','HR','Financial') NOT NULL,
  `Job` enum('Accountant','Manager','HR','IT','Financial') NOT NULL,
  `Comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `comment`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `comment_ibfk_1` (`ID`);

CREATE TABLE `current account` (
  `Account Number` int(11) NOT NULL,
  `Money` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `current account`
  ADD PRIMARY KEY (`Account Number`);

CREATE TABLE `employee` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Age` int(11) NOT NULL,
  `National ID` varchar(255) NOT NULL,
  `Address` int(11) NOT NULL,
  `Phone Number` varchar(255) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Gender` enum('Male','Female') NOT NULL,
  `Department` enum('Manager','Accounting','IT','HR','Financial') NOT NULL,
  `Job` enum('Accountant','Manager','HR','IT','Financial') NOT NULL,
  `Salary` double NOT NULL,
  `Computer Number` int(11) NOT NULL,
  `In` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `employee`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Computer Number` (`Computer Number`),
  ADD UNIQUE KEY `National ID` (`National ID`),
  ADD UNIQUE KEY `Email` (`Email`) USING HASH,
  ADD KEY `Address` (`Address`);

CREATE TABLE `employeelogin` (
  `Number` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `Username` varchar(40) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Password` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `employeelogin`
  ADD PRIMARY KEY (`Number`),
  ADD UNIQUE KEY `ID` (`ID`),
  ADD UNIQUE KEY `Username` (`Username`) USING HASH,
  ADD UNIQUE KEY `Email` (`Email`) USING HASH;

CREATE TABLE `enquiry` (
  `Number` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Phone Number` varchar(11) NOT NULL,
  `Customer Enquiry` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `enquiry`
  ADD PRIMARY KEY (`Number`),
  ADD UNIQUE KEY `Email` (`Email`);

CREATE TABLE `financial transactions` (
  `Number` int(11) NOT NULL,
  `Date` int(11) NOT NULL,
  `Account Number 1` int(11) NOT NULL,
  `Account Number 2` int(11) NOT NULL,
  `Transition` enum('Deposit','Withdraw','Transfer/Sender','Transfer/Receiver','New Account','Loan','Paid Loan','New Loan','Withdraw Loan','Received Remaining Loan','Send Remaining Loan') NOT NULL,
  `Type` enum('Saving Account','Current Account','Loan') NOT NULL,
  `Amount` double NOT NULL,
  `Money` double NOT NULL,
  `Current Money` double NOT NULL,
  `Calculated Benefits` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `financial transactions`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `financial transactions_ibfk_1` (`Account Number 1`),
  ADD KEY `financial transactions_ibfk_2` (`Account Number 2`);

CREATE TABLE `loan` (
  `Number` int(11) NOT NULL,
  `Account Number` int(11) NOT NULL,
  `From` int(11) NOT NULL,
  `To` int(11) NOT NULL,
  `Amount` double NOT NULL,
  `Remaining` double NOT NULL,
  `Withdraw` double NOT NULL,
  `Guarantee` enum('Personal Guarantee','Goods Guarantee','Property Guarantee') NOT NULL,
  `Paid` tinyint(4) NOT NULL,
  `Close` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `loan`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `Account Number` (`Account Number`);

CREATE TABLE `loan paid` (
  `Number` int(11) NOT NULL,
  `Date` int(11) NOT NULL,
  `Account Number` int(11) NOT NULL,
  `Money` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `loan paid`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `Account Number` (`Account Number`);

CREATE TABLE `loan reminder` (
  `Number` int(11) NOT NULL,
  `Date` int(11) NOT NULL,
  `Account Number` int(11) NOT NULL,
  `Reminder` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `loan reminder`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `Account Number` (`Account Number`);

CREATE TABLE `loan warning` (
  `Number` int(11) NOT NULL,
  `Date` int(11) NOT NULL,
  `Account Number` int(11) NOT NULL,
  `Warning` text NOT NULL,
  `Seen` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `loan warning`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `Account Number` (`Account Number`);

CREATE TABLE `report` (
  `Number` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Department` enum('Manager','Accounting','IT','HR','Financial') NOT NULL,
  `Computer Number` int(11) NOT NULL,
  `Report` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `report`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `report_ibfk_1` (`ID`);

CREATE TABLE `saving account` (
  `Date` varchar(4) NOT NULL,
  `Account Number` int(11) NOT NULL,
  `Money` double NOT NULL,
  `Benefits` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `saving account`
  ADD PRIMARY KEY (`Account Number`);

CREATE TABLE `storage` (
  `Number` int(11) NOT NULL,
  `Date` int(11) NOT NULL,
  `Account Number` int(11) NOT NULL,
  `Money` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `storage`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `storage_ibfk_1` (`Account Number`);

CREATE TABLE `task` (
  `Number` int(11) NOT NULL,
  `Date` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `Job` enum('Accountant','Manager','HR','IT','Financial') NOT NULL,
  `Task` text NOT NULL,
  `Seen` tinyint(1) NOT NULL,
  `Done` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `task`
  ADD PRIMARY KEY (`Number`),
  ADD KEY `task_ibfk_1` (`ID`);




ALTER TABLE `apply for visa`
  ADD CONSTRAINT `apply for visa_ibfk_1` FOREIGN KEY (`Address`) REFERENCES `address` (`Number`);

ALTER TABLE `benefits paid`
  ADD CONSTRAINT `benefits paid_ibfk_1` FOREIGN KEY (`Account Number`) REFERENCES `saving account` (`Account Number`);

ALTER TABLE `benefits received`
  ADD CONSTRAINT `benefits received_ibfk_1` FOREIGN KEY (`Account Number`) REFERENCES `client` (`Account Number`);

ALTER TABLE `benefits transactions`
  ADD CONSTRAINT `benefits transactions_ibfk_1` FOREIGN KEY (`Account Number`) REFERENCES `client` (`Account Number`);

ALTER TABLE `client`
  ADD CONSTRAINT `client_ibfk_1` FOREIGN KEY (`Address`) REFERENCES `address` (`Number`);

ALTER TABLE `clientlogin`
  ADD CONSTRAINT `clientlogin_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `client` (`Account Number`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `employee` (`ID`) ON UPDATE CASCADE;

ALTER TABLE `current account`
  ADD CONSTRAINT `current account_ibfk_1` FOREIGN KEY (`Account Number`) REFERENCES `client` (`Account Number`);

ALTER TABLE `employee`
  ADD CONSTRAINT `Address` FOREIGN KEY (`Address`) REFERENCES `address` (`Number`);

ALTER TABLE `employeelogin`
  ADD CONSTRAINT `employeelogin_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `employee` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `financial transactions`
  ADD CONSTRAINT `financial transactions_ibfk_1` FOREIGN KEY (`Account Number 1`) REFERENCES `client` (`Account Number`) ON UPDATE CASCADE,
  ADD CONSTRAINT `financial transactions_ibfk_2` FOREIGN KEY (`Account Number 2`) REFERENCES `client` (`Account Number`) ON UPDATE CASCADE;

ALTER TABLE `loan`
  ADD CONSTRAINT `loan_ibfk_1` FOREIGN KEY (`Account Number`) REFERENCES `client` (`Account Number`);

ALTER TABLE `loan paid`
  ADD CONSTRAINT `loan paid_ibfk_1` FOREIGN KEY (`Account Number`) REFERENCES `loan` (`Account Number`);

ALTER TABLE `loan reminder`
  ADD CONSTRAINT `loan reminder_ibfk_1` FOREIGN KEY (`Account Number`) REFERENCES `loan` (`Account Number`);

ALTER TABLE `loan warning`
  ADD CONSTRAINT `loan warning_ibfk_1` FOREIGN KEY (`Account Number`) REFERENCES `loan` (`Account Number`);

ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `employee` (`ID`) ON UPDATE CASCADE;

ALTER TABLE `saving account`
  ADD CONSTRAINT `saving account_ibfk_1` FOREIGN KEY (`Account Number`) REFERENCES `client` (`Account Number`);

ALTER TABLE `storage`
  ADD CONSTRAINT `storage_ibfk_1` FOREIGN KEY (`Account Number`) REFERENCES `client` (`Account Number`) ON UPDATE CASCADE;

ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `employee` (`ID`) ON UPDATE CASCADE;
COMMIT;

INSERT INTO `address` (`Number`, `City`, `Street`, `Home Number`) VALUES
(0, '00', '00', 0);

INSERT INTO `bank account` (`Account Number`, `Type`, `Money`) VALUES
(0, 'Storage', 0),
(1, 'Benefits Paid', 0),
(2, 'Benefits Received', 0),
(3, 'Profits', 0),
(4, 'Loss', 0),
(5, 'Incoming', 0);

INSERT INTO `client` (`Account Number`, `Name`, `Age`, `National ID`, `Address`, `Phone Number`, `Email`, `Gender`, `Account Type`) VALUES
(0, '', 0, 0, 0, '0', '0', 'None', 'None');
