drop database if exists mydatabase; 
create database mydatabase;

use mydatabase;

create table `Substances`(
	`formula` char(16) not null,
	`name` char(16),
	`phase` char(2) not null,
	`melt` int,
	`boil` int,
	`as` char(3) not null,
	primary key(formula)
);

create table `Element`(
	`symbol` char(2) not null,
	`name` char(10),
	`elementID` int not null,
	`weight` float,
	primary key(symbol)
);

create table `Composition`(
	`formula` char(16) not null,
	`symbol` char(2) not null,
	`number` int,
	primary key(formula,symbol)
);

create table `Crystal`(
	`formula` char(16) not null,
	`bonding` char(10) not null,
	`structure` char(20) not null,
	primary key(formula)
);

create table `Bonding`(
	`bonding` char(10) not null,
	`crystal` char(10) not null,
	primary key(bonding)
);


insert into Substances values
('H2O','水','液体','0','100','中性'),
('Au','金','固体','1064','2857','中性'),
('O2','酸素','気体','-219','-183','中性'),
('O3','オゾン','気体','-193','-112','中性'),
('NH3','アンモニア','気体','-78','-33','塩基性'),
('NaCl','塩化ナトリウム','固体','800','1413','中性'),
('H2SO4','硫酸','液体','10','290','酸性'),
('CH3COOH','酢酸','液体','17','118','酸性'),
('Si','ケイ素','固体','1414','2355','中性');


insert into Element values
('H','水素','1','1.008'),
('C','炭素','6','12.01'),
('N','窒素','7','14.01'),
('O','酸素','8','16.00'),
('Na','ナトリウム','11','22.99'),
('Si','ケイ素','14','28.09'),
('S','硫黄','16','32.07'),
('Cl','塩素','17','35.45'),
('Au','金','79','197.0');


insert into Composition values
('H2O','H','2'),
('H2O','O','1'),
('Au','Au','1'),
('O2','O','2'),
('O3','O','3'),
('NH3','N','1'),
('NH3','H','3'),
('NaCl','Na','1'),
('NaCl','Cl','1'),
('H2SO4','H','2'),
('H2SO4','S','1'),
('H2SO4','O','4'),
('CH3COOH','C','2'),
('CH3COOH','H','4'),
('CH3COOH','O','2'),
('Si','Si','1');


insert into Crystal values
('Au','金属結合','面心立法格子構造'),
('Nacl','イオン結合','塩化ナトリウム型構造'),
('Si','共有結合','ダイヤモンド構造');


insert into Bonding values
('イオン結合','イオン結晶'),
('共有結合','共有結合結晶'),
('ファンデルワールス力','分子結晶'),
('水素結合','分子結晶'),
('金属結合','金属結晶');
