
CREATE TABLE [Gradovi] (
  [Id]      INT PRIMARY KEY IDENTITY (1, 1) NOT NULL,
  [Naziv]     NVARCHAR (50) NOT NULL,
  [Postanski_broj]     CHAR (5) NOT NULL
);

INSERT INTO [Gradovi]([Naziv], [Postanski_broj]) 
VALUES 
('Zagreb', '10000'),
('Velika Gorica', '10410'),
('Split', '21000'),
('Osijek', '31000'),
('Rijeka', '51000');


CREATE TABLE [PravaKorisnika] (
  [Id]      INT PRIMARY KEY IDENTITY (1, 1) NOT NULL,
  [Naziv]     NVARCHAR (50) NOT NULL,
  [Zaposlenik] BIT NOT NULL DEFAULT 0
);

INSERT INTO [PravaKorisnika]([Naziv], [Zaposlenik]) 
VALUES 
('Pacijent', 0),
('Medicinsko osoblje', 1),
('Administrator', 1),
('Doktor', 1),
('Aplikacija', 0);

drop table [Korisnici]

CREATE TABLE [Korisnici] (
    [Id]      INT PRIMARY KEY IDENTITY (1, 1) NOT NULL,
    [Ime]     NVARCHAR (50) NOT NULL,
    [Prezime] NVARCHAR (50) NOT NULL,
    [Oib]   CHAR (11) NOT NULL,
    [MaticniBrojOsiguranika]   CHAR (9),
    [Adresa] NVARCHAR (50) NOT NULL,
    [Grad_Id] int NOT NULL references [Gradovi]([Id]),
    [KorisnickoIme] NVARCHAR(50) UNIQUE NOT NULL,
    [Lozinka] NVARCHAR(50) NOT NULL,
    [Email]   NVARCHAR (50) NOT NULL,
    [Aktivan] BIT NOT NULL DEFAULT 1,
    [Uloga_Id] int NOT NULL references [PravaKorisnika]([Id]),
    [VrijemeKreiranja] DATETIME DEFAULT GETDATE() NOT NULL
);

INSERT INTO [Korisnici]([Ime], [Prezime], [Oib], [MaticniBrojOsiguranika], [Adresa], [Grad_Id], [KorisnickoIme], [Lozinka], [Email], [Aktivan], [Uloga_Id], [VrijemeKreiranja]]) 
VALUES 
('admin', 'admin', '12345678901', '', 'Zagrebačka 5', 2, 'admin', 'admin123', 'admin@iskon.hr', 1, 3, GETDATE()),
('pero', 'peric', '12345678902', '123456789', 'Ilica 10', 1, 'pacijent', 'pacijent123', 'pacijent@tcom.hr', 1, 1, GETDATE()),
('tomo', 'tomic', '12345678903', '', 'Zvonimirova 50', 1, 'zaposlenik', 'zaposlenik123', 'zaposlenik@tcom.hr', 1, 2, GETDATE()),
('dr ivo', 'ivic', '12345678904', '', 'Branimirova 7', 1, 'doktor1', 'doktor123', 'doktor1@tcom.hr', 1, 4, GETDATE()),
('dr marko', 'markic', '12345678905', '', 'Domogojeva 23', 1, 'doktor2', 'doktor223', 'doktor2@tcom.hr', 1, 4, GETDATE());

CREATE TABLE [Bolnice] (
    [Id]      INT PRIMARY KEY IDENTITY (1, 1) NOT NULL,
    [Naziv]     NVARCHAR (100) NOT NULL,
    [Adresa] NVARCHAR (50) NOT NULL,
    [Grad_Id] int NOT NULL references [Gradovi]([Id]),
    [Email]   NVARCHAR (50) NOT NULL
);

INSERT INTO [Bolnice]([Naziv], [Adresa], [Grad_Id], [Email]) 
VALUES 
('KBC Zagreb', 'Kišpatićeva 12', 1, 'kbcrebro@iskon.hr'),
('Klinika za plućne bolesti', 'Jordanovac 104', 1, 'kbcjordanovac@tcom.hr'),
('Klinika za ženske bolesti i porode', 'Petrova 13', 1, 'kbcpetrova@tcom.hr'),
('Klinika Šalata', 'Šalata 7', 1, 'kbcslt@tcom.hr'),
('Klinika Nazorova', 'Nazorova 49', 1, 'kbcnazorova@tcom.hr'),
('Klinika za stomatologiju', 'Gundulićeva 5', 1, 'kbcgunduliceva@tcom.hr'),
('Klinički zavod za rehabilitaciju i ortopedska pomagala', 'Božidarevićeva 11', 1, 'kbcbozidariviceva@tcom.hr');

CREATE TABLE [MedicinskiPostupci] (
  [Id]      INT PRIMARY KEY IDENTITY (1, 1) NOT NULL,
  [Naziv]     NVARCHAR (50) NOT NULL
);

INSERT INTO [MedicinskiPostupci]([Naziv]) 
VALUES 
('Operativno liječenje prijeloma'),
('Lasersko liječenje bolesti oka'),
('Fizikalna terapija'),
('Zračenje tumora');

racuni

CREATE TABLE [Racuni] (
    [Id]      INT PRIMARY KEY IDENTITY (1, 1) NOT NULL,
    [BrojRacuna] CHAR (12),
    [DatumRacuna] DATE DEFAULT GETDATE() NOT NULL,
    [Korisnik_Id] int NOT NULL references [Korisnici]([Id]),
    [Iznos] decimal(18, 2) NOT NULL,
	[Placen] BIT NOT NULL DEFAULT 0, 
	[Aktivan] BIT NOT NULL DEFAULT 1, 
	[DatumKreiranjaRacuna] DATE DEFAULT GETDATE() NOT NULL,
    [Kreirao_Id] int NOT NULL references [Korisnici]([Id])
);

CREATE TABLE [Termini] (
    [Id]      INT PRIMARY KEY IDENTITY (1, 1) NOT NULL,
    [DatumTermina] DATE DEFAULT GETDATE() NOT NULL,
    [Bolnica_Id] int NOT NULL references [Bolnice]([Id]),
    [MedicinskiPostupak_Id] int NOT NULL references [MedicinskiPostupci]([Id])
);

CREATE TABLE [StatusiNarudzbe] (
    [Id]      INT PRIMARY KEY IDENTITY (1, 1) NOT NULL,
    [Naziv]     NVARCHAR (50) NOT NULL
);

INSERT INTO [StatusiNarudzbe]([Naziv]) 
VALUES 
('Aktivna'),
('Otkazana'),
('Iskorištena');

CREATE TABLE [Narudzbe] (
    [Id]      INT PRIMARY KEY IDENTITY (1, 1) NOT NULL,
    [Korisnik_Id] int NOT NULL references [Korisnici]([Id]),
    [BrojNarudzbe] CHAR (12),
    [Termin_Id] int NOT NULL references [Termini]([Id]),
    [DatumKreiranjaNarudzbe] DATE DEFAULT GETDATE() NOT NULL,
    [Status_Id] int NOT NULL DEFAULT 1 references [StatusiNarudzbe]([Id]),
    [Kreirao_Id] int NOT NULL references [Korisnici]([Id]),
);

CREATE TABLE [Lijecenja] (
    [Id]      INT PRIMARY KEY IDENTITY (1, 1) NOT NULL,
    [Narudzba_Id] int NOT NULL references [Narudzbe]([Id]),
    [DatumLijecenja] DATE DEFAULT GETDATE() NOT NULL,
    [Lijecio_Id] int NOT NULL references [Korisnici]([Id]),
	[OpisLijecenja]     NVARCHAR (2000) NOT NULL
);


