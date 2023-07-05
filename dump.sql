create database db_projetoIE;
use db_projetoIE;

create table tb_categorias(cat_id int(11) primary key auto_increment, cat_desc varchar(255));
create table tb_documentos(doc_id int(11) primary key auto_increment, doc_url varchar(255), doc_categoria int(11), doc_nivel int(11));
create table tb_favoritos(fav_id int(11) primary key auto_increment, fav_documento int(11), fav_usu int(11));
create table tb_niveis(niv_id int(11) primary key auto_increment, niv_desc varchar(255));
create table tb_usuarios(usu_id int(11) primary key auto_increment, usu_nome varchar(255), usu_email varchar(255), usu_senha varchar(255));
alter table tb_documentos add column doc_desc varchar(255);
alter table tb_documentos add column doc_usu int(11);
alter table tb_documentos add column doc_datacad varchar(8);
alter table tb_usuarios add column usu_permissao int(11);

insert into tb_categorias(cat_desc) VALUES ('Algoritmos');
insert into tb_categorias(cat_desc) VALUES ('Física');
insert into tb_categorias(cat_desc) VALUES ('Química');
insert into tb_categorias(cat_desc) VALUES ('Orientação a objetos');
insert into tb_categorias(cat_desc) VALUES ('Português');
insert into tb_categorias(cat_desc) VALUES ('História');
insert into tb_categorias(cat_desc) VALUES ('Economia');
insert into tb_categorias(cat_desc) VALUES ('Matemática');
insert into tb_categorias(cat_desc) VALUES ('Filosofia');
insert into tb_categorias(cat_desc) VALUES ('Cálculo');

insert into tb_niveis(niv_desc) VALUES ('Fundamental');
insert into tb_niveis(niv_desc) VALUES ('Médio');
insert into tb_niveis(niv_desc) VALUES ('Superior');

-- Login: milena@compassio.com.br senha: teste123