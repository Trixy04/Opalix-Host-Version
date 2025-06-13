-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Giu 13, 2025 alle 16:57
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `opalix_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `articoli`
--

CREATE TABLE `articoli` (
  `id` int(11) NOT NULL,
  `codice_articolo` varchar(255) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `descrizione` text DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `marca_id` int(11) DEFAULT NULL,
  `materiale_id` int(11) DEFAULT NULL,
  `peso_materiale` decimal(10,2) DEFAULT NULL,
  `carati_materiale` decimal(10,2) DEFAULT NULL,
  `prezzo_acquisto` decimal(10,2) DEFAULT NULL,
  `prezzo_vendita` decimal(10,2) DEFAULT NULL,
  `quantita` int(11) DEFAULT NULL,
  `ubicazione` varchar(255) DEFAULT NULL,
  `stato_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `articoli`
--

INSERT INTO `articoli` (`id`, `codice_articolo`, `nome`, `descrizione`, `categoria_id`, `marca_id`, `materiale_id`, `peso_materiale`, `carati_materiale`, `prezzo_acquisto`, `prezzo_vendita`, `quantita`, `ubicazione`, `stato_id`, `note`, `foto`) VALUES
(1, '34242', 'vdsfds', NULL, 1, 3, 2, 1.00, 1.00, 1111.00, 12090.00, 1, NULL, 2, NULL, 'assets/articoli/foto_684c240545a0f4.63991028.jpeg');

-- --------------------------------------------------------

--
-- Struttura della tabella `articoli_pietre`
--

CREATE TABLE `articoli_pietre` (
  `id` int(11) NOT NULL,
  `id_pietra` int(11) NOT NULL,
  `id_articolo` int(11) NOT NULL,
  `qta_pietra` int(11) NOT NULL,
  `caratura_pietra` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `articoli_pietre`
--

INSERT INTO `articoli_pietre` (`id`, `id_pietra`, `id_articolo`, `qta_pietra`, `caratura_pietra`) VALUES
(1, 16, 1, 1, 1),
(2, 5, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `categorie`
--

INSERT INTO `categorie` (`id`, `nome`) VALUES
(1, 'Anello'),
(2, 'Bracciale'),
(3, 'Collana'),
(4, 'Orecchini'),
(5, 'Ciondolo'),
(6, 'Spilla'),
(7, 'Girocollo'),
(8, 'Catena'),
(9, 'Gemelli'),
(10, 'Piercing'),
(11, 'Parure'),
(12, 'Cavigliera'),
(13, 'Rosario'),
(14, 'Medaglia'),
(15, 'Tiarra'),
(16, 'Ferma cravatta');

-- --------------------------------------------------------

--
-- Struttura della tabella `clienti`
--

CREATE TABLE `clienti` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `codice_fiscale` varchar(16) NOT NULL,
  `data_nascita` date NOT NULL,
  `data_creazione` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `clienti`
--

INSERT INTO `clienti` (`id`, `nome`, `cognome`, `codice_fiscale`, `data_nascita`, `data_creazione`) VALUES
(1, 'Ronaldo', 'Perozzo', 'FQERYL69L24N391C', '2004-04-07', '2025-06-13 13:37:34'),
(2, 'Michelotto', 'Montanariello', 'JBXWSD09J57T036T', '2002-02-10', '2025-06-13 13:37:34'),
(3, 'Gianni', 'Morosini', 'VLJCPO14B07K939H', '2002-12-24', '2025-06-13 13:37:34'),
(4, 'Anita', 'Zacchia', 'MQLAEQ32N82C054S', '1953-09-26', '2025-06-13 13:37:34'),
(5, 'Paola', 'Bandello', 'IPBPKA35K34N192A', '1963-04-29', '2025-06-13 13:37:34'),
(6, 'Rodolfo', 'Finetti', 'UVKDIK70E34H213Z', '1940-10-20', '2025-06-13 13:37:34'),
(7, 'Marissa', 'Crespi', 'BAOTEH64U07N230Q', '1989-03-20', '2025-06-13 13:37:34'),
(8, 'Raffaella', 'Fantoni', 'RYGDVT36I79V182R', '1944-09-27', '2025-06-13 13:37:34'),
(9, 'Liliana', 'Nonis', 'JEZFCS54S36L899B', '1970-01-30', '2025-06-13 13:37:34'),
(10, 'Giovanni', 'Carocci', 'IWOSXD37O15Q952C', '1941-04-28', '2025-06-13 13:37:34'),
(11, 'Ettore', 'Capecchi', 'TSHVDR29D65V420L', '1948-02-05', '2025-06-13 13:37:34'),
(12, 'Giorgia', 'Fogazzaro', 'XHIRNC00S97J379R', '2006-04-11', '2025-06-13 13:37:34'),
(13, 'Roberto', 'Trapani', 'FABHZM71I68J298D', '1987-02-07', '2025-06-13 13:37:34'),
(14, 'Salvi', 'Scarponi', 'CPKEZP75U20Q832S', '1956-04-19', '2025-06-13 13:37:34'),
(15, 'Costantino', 'Cagnin', 'COFTLY14X27J291U', '1965-01-07', '2025-06-13 13:37:34'),
(16, 'Pina', 'Zaccagnini', 'OONNQW54G95C080W', '1957-12-22', '2025-06-13 13:37:34'),
(17, 'Cassandra', 'Dandolo', 'XDVLJD21H41E352R', '1940-12-13', '2025-06-13 13:37:34'),
(18, 'Jacopo', 'Turati', 'KTCKQT74S77Y092B', '1941-01-01', '2025-06-13 13:37:34'),
(19, 'Lazzaro', 'Parisi', 'QERZIC41T84Q353K', '1935-08-18', '2025-06-13 13:37:34'),
(20, 'Telemaco', 'Gigli', 'QGKGZJ06S58N914J', '1979-10-22', '2025-06-13 13:37:34'),
(21, 'Uberto', 'Barillaro', 'JUAIJT96Z77K241B', '1974-06-07', '2025-06-13 13:37:34'),
(22, 'Tatiana', 'Barracco', 'YYLCIE98X82Q138A', '1995-04-05', '2025-06-13 13:37:34'),
(23, 'Sophia', 'Cendron', 'IDKRLV47Y41G963U', '1948-10-09', '2025-06-13 13:37:34'),
(24, 'Bernardo', 'Fantoni', 'TCPUVS78P90F848W', '1963-12-07', '2025-06-13 13:37:34'),
(25, 'Enzio', 'Soprano', 'OUXNYS62L33Y571E', '1990-04-26', '2025-06-13 13:37:34'),
(26, 'Gastone', 'Marsili', 'ITVRBS24A25V195V', '1978-08-16', '2025-06-13 13:37:34'),
(27, 'Elmo', 'Boitani', 'KIEFVC68O46A090W', '1955-12-06', '2025-06-13 13:37:34'),
(28, 'Fiorenzo', 'Corcos', 'WSOBAP62H01A211E', '1960-07-02', '2025-06-13 13:37:34'),
(29, 'Sebastiano', 'Vercelloni', 'HOWDFG36G71O745C', '2000-12-03', '2025-06-13 13:37:34'),
(30, 'Adamo', 'Abatantuono', 'XVJIDD76J51K578B', '1980-05-07', '2025-06-13 13:37:34'),
(31, 'Laura', 'Comboni', 'IRBKNR55O99K669M', '1994-01-01', '2025-06-13 13:37:34'),
(32, 'Pellegrino', 'Cendron', 'HGSIDG46R11E508M', '1987-04-14', '2025-06-13 13:37:34'),
(33, 'Mariana', 'Offredi', 'ATHTBV27Y78F831K', '1980-08-07', '2025-06-13 13:37:34'),
(34, 'Roberto', 'Florio', 'BYPGWW76O74Q053A', '1960-02-21', '2025-06-13 13:37:34'),
(35, 'Cirillo', 'Papafava', 'DOCNTR41G33G886X', '1986-08-31', '2025-06-13 13:37:34'),
(36, 'Girolamo', 'Ligorio', 'QQADPU42M76E079D', '1972-04-28', '2025-06-13 13:37:34'),
(37, 'Alderano', 'Roncalli', 'KWWFNI33X90Z471J', '1973-06-08', '2025-06-13 13:37:34'),
(38, 'Giacomo', 'Conti', 'RTAMPS71Q08P554Q', '1970-12-26', '2025-06-13 13:37:34'),
(39, 'Claudio', 'Pincherle', 'CJPLWC23K95V046V', '1945-04-05', '2025-06-13 13:37:34'),
(40, 'Victoria', 'Lanfranchi', 'MIHJQP80H00G122T', '1958-03-06', '2025-06-13 13:37:34'),
(41, 'Vittorio', 'Interminei', 'HGKIGS26L52Z484M', '1936-08-07', '2025-06-13 13:37:34'),
(42, 'Melania', 'Paolini', 'TEJPCF21X85J923Z', '2000-01-05', '2025-06-13 13:37:34'),
(43, 'Camilla', 'Cugia', 'JSZXSA29R12M022P', '1952-08-11', '2025-06-13 13:37:34'),
(44, 'Licia', 'Federico', 'MFPSVI14P95B734R', '1975-09-08', '2025-06-13 13:37:34'),
(45, 'Baldassare', 'Bosurgi', 'YLEXST94N08E851I', '1970-04-22', '2025-06-13 13:37:34'),
(46, 'Roman', 'Magnani', 'UXVTWA68T93S255C', '1943-05-17', '2025-06-13 13:37:34'),
(47, 'Fiamma', 'Salieri', 'LLUAML48V94S612O', '1981-10-09', '2025-06-13 13:37:34'),
(48, 'Giulio', 'Mascagni', 'XGXSPV65Q91B388M', '1987-11-24', '2025-06-13 13:37:34'),
(49, 'Gilberto', 'Bonino', 'KXVVUN39L31T360L', '1985-12-24', '2025-06-13 13:37:34'),
(50, 'Eleanora', 'Borromini', 'HIHWPP85E86J165K', '1989-01-14', '2025-06-13 13:37:34'),
(51, 'Mattia', 'Teriaca', 'TRCMTT04P13D612R', '2004-09-13', '2025-06-13 15:46:43');

-- --------------------------------------------------------

--
-- Struttura della tabella `clienti_contatti`
--

CREATE TABLE `clienti_contatti` (
  `cliente_id` int(11) NOT NULL,
  `contatto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `clienti_contatti`
--

INSERT INTO `clienti_contatti` (`cliente_id`, `contatto_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15),
(16, 16),
(17, 17),
(18, 18),
(19, 19),
(20, 20),
(21, 21),
(22, 22),
(23, 23),
(24, 24),
(25, 25),
(26, 26),
(27, 27),
(28, 28),
(29, 29),
(30, 30),
(31, 31),
(32, 32),
(33, 33),
(34, 34),
(35, 35),
(36, 36),
(37, 37),
(38, 38),
(39, 39),
(40, 40),
(41, 41),
(42, 42),
(43, 43),
(44, 44),
(45, 45),
(46, 46),
(47, 47),
(48, 48),
(49, 49),
(50, 50),
(51, 51);

-- --------------------------------------------------------

--
-- Struttura della tabella `clienti_indirizzi`
--

CREATE TABLE `clienti_indirizzi` (
  `cliente_id` int(11) NOT NULL,
  `indirizzo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `clienti_indirizzi`
--

INSERT INTO `clienti_indirizzi` (`cliente_id`, `indirizzo_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15),
(16, 16),
(17, 17),
(18, 18),
(19, 19),
(20, 20),
(21, 21),
(22, 22),
(23, 23),
(24, 24),
(25, 25),
(26, 26),
(27, 27),
(28, 28),
(29, 29),
(30, 30),
(31, 31),
(32, 32),
(33, 33),
(34, 34),
(35, 35),
(36, 36),
(37, 37),
(38, 38),
(39, 39),
(40, 40),
(41, 41),
(42, 42),
(43, 43),
(44, 44),
(45, 45),
(46, 46),
(47, 47),
(48, 48),
(49, 49),
(50, 50),
(51, 51);

-- --------------------------------------------------------

--
-- Struttura della tabella `configurazione_azienda`
--

CREATE TABLE `configurazione_azienda` (
  `id` int(11) NOT NULL,
  `ragione_sociale` varchar(255) NOT NULL,
  `partita_iva` varchar(20) DEFAULT NULL,
  `codice_fiscale` varchar(20) DEFAULT NULL,
  `indirizzo` varchar(255) DEFAULT NULL,
  `cap` varchar(10) DEFAULT NULL,
  `citta` varchar(100) DEFAULT NULL,
  `provincia` varchar(2) DEFAULT NULL,
  `paese` varchar(100) DEFAULT 'Italia',
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `pec` varchar(150) DEFAULT NULL,
  `sito_web` varchar(150) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `colore_tema` varchar(7) DEFAULT NULL,
  `modalita_gestione` enum('standard','avanzata') DEFAULT 'standard',
  `note` text DEFAULT NULL,
  `data_creazione` datetime DEFAULT current_timestamp(),
  `data_aggiornamento` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `configurazione_azienda`
--

INSERT INTO `configurazione_azienda` (`id`, `ragione_sociale`, `partita_iva`, `codice_fiscale`, `indirizzo`, `cap`, `citta`, `provincia`, `paese`, `telefono`, `email`, `pec`, `sito_web`, `logo_path`, `colore_tema`, `modalita_gestione`, `note`, `data_creazione`, `data_aggiornamento`) VALUES
(1, 'Opalix S.r.l.', '12345678901', '12345678901', 'Via dei Diamanti 12', '00100', 'Roma', 'RM', 'Italia', '+39 06 1234567', 'info@opalix.it', 'opalix@pec.it', 'https://www.opalix.it', '/assets/logo_opalix.png', '#2C3E50', 'avanzata', 'Sistema ERP per la gestione completa della gioielleria.', '2025-06-13 15:42:39', '2025-06-13 15:42:39');

-- --------------------------------------------------------

--
-- Struttura della tabella `contatti`
--

CREATE TABLE `contatti` (
  `id` int(11) NOT NULL,
  `cellulare` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `contatti`
--

INSERT INTO `contatti` (`id`, `cellulare`, `email`) VALUES
(1, '+39 44 8018619', 'mattarellarolando@ricci-curatoli.it'),
(2, '+39 492 76 34 9634', 'poleselmarcantonio@vasari.it'),
(3, '+39 062 04 31 3427', 'peanoamedeo@vasari.net'),
(4, '+39 913 62876699', 'finettiaugusto@tin.it'),
(5, '+39 672 7936121', 'giacintocampano@tiscali.com'),
(6, '+39 679 34519819', 'ncerquiglini@gori.eu'),
(7, '+39 6774 05021936', 'ytremonti@orlando.net'),
(8, '+39 8083 2353636', 'emilio11@vodafone.it'),
(9, '+39 741 89736522', 'papafavavittoria@tim.it'),
(10, '+39 281 0178465', 'perandaloretta@vodafone.it'),
(11, '+39 108 88632028', 'ezioperozzo@alice.it'),
(12, '+39 397 11919584', 'leonemarini@verdi-sonnino.it'),
(13, '+39 900 52333347', 'antonettisophia@foa-naccari.eu'),
(14, '+39 809 87486065', 'ivanmaglio@persico.it'),
(15, '+39 772 17164612', 'barbara77@montalti-pelli.com'),
(16, '+39 262 55165042', 'onistomassimo@libero.it'),
(17, '+39 69 5403206', 'eeco@libero.it'),
(18, '+39 475 96478623', 'vcammarata@tin.it'),
(19, '+39 988 57 32 0570', 'pagnottomarcella@cocci.com'),
(20, '+39 8830 46470465', 'dellemilo@micheletti-guariento.it'),
(21, '+39 782 94 31 2367', 'maurizio51@gmail.com'),
(22, '+39 184 78056346', 'paridesalvo@gmail.com'),
(23, '+39 3845 30959790', 'sandrochigi@gmail.com'),
(24, '+39 782 15 12 5650', 'gustavo45@gmail.com'),
(25, '+39 557 77 83 8138', 'ipedrazzini@natta.com'),
(26, '+39 37 9460423', 'mondainirosalia@catalano.it'),
(27, '+39 0886 9220573', 'malacarnepietro@fiorucci.it'),
(28, '+39 332 5650555', 'crispitiziana@outlook.com'),
(29, '+39 834 5047418', 'lolitafarina@columbo.com'),
(30, '+39 503 9764023', 'irma90@libero.it'),
(31, '+39 451 2486354', 'raimondimartino@tim.it'),
(32, '+39 995 9884317', 'qbarcella@gmail.com'),
(33, '+39 7668 5606128', 'francesca76@libero.it'),
(34, '+39 68 70301379', 'bottapatrizia@hotmail.com'),
(35, '+39 76 15358520', 'robertozaguri@campano.it'),
(36, '+39 517 9857529', 'calcedonio74@cibin-einaudi.com'),
(37, '+39 850 95251123', 'giorgio15@udinesi.eu'),
(38, '+39 068 06913732', 'casagrandegoffredo@outlook.com'),
(39, '+39 087 93 65 7166', 'vanessa96@libero.it'),
(40, '+39 0665 0477195', 'trillininicolo@curci.com'),
(41, '+39 2028 5228097', 'nattamartino@virgilio.it'),
(42, '+39 301 03 66 2268', 'gulottarita@yahoo.com'),
(43, '+39 85 63440623', 'ccalvo@castellitto-verga.it'),
(44, '+39 4699 48427030', 'eluxardo@outlook.com'),
(45, '+39 0943 36567538', 'renzipompeo@hotmail.it'),
(46, '+39 37 2310303', 'ernestolombardi@castelli.org'),
(47, '+39 082 5316434', 'oasmundo@alice.it'),
(48, '+39 439 41 63 1960', 'ettoretravaglio@cavanna.it'),
(49, '+39 131 13043053', 'eliana41@persico.com'),
(50, '+39 647 58271541', 'bertonisabatino@hotmail.com'),
(51, '3911803593', 'teriaca.mattia@teriacamattia.it');

-- --------------------------------------------------------

--
-- Struttura della tabella `contatti_fornitori`
--

CREATE TABLE `contatti_fornitori` (
  `id` int(11) NOT NULL,
  `fornitore_id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `valore` varchar(255) NOT NULL,
  `descrizione` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `contatti_fornitori`
--

INSERT INTO `contatti_fornitori` (`id`, `fornitore_id`, `tipo`, `valore`, `descrizione`) VALUES
(1, 1, 'telefono', '+390612345001', 'Ufficio principale'),
(2, 1, 'email', 'info@alfa.it', 'Email generale'),
(3, 2, 'telefono', '+390262345002', 'Ufficio principale'),
(4, 2, 'email', 'info@beta.it', 'Email generale'),
(5, 3, 'telefono', '+390812345003', 'Ufficio principale'),
(6, 3, 'email', 'info@gamma.it', 'Email generale'),
(7, 4, 'telefono', '+390112345004', 'Ufficio principale'),
(8, 4, 'email', 'info@delta.it', 'Email generale'),
(9, 5, 'telefono', '+390512345005', 'Ufficio principale'),
(10, 5, 'email', 'info@epsilon.it', 'Email generale'),
(11, 6, 'telefono', '+390512345006', 'Ufficio principale'),
(12, 6, 'email', 'info@zeta.it', 'Email generale'),
(13, 7, 'telefono', '+390412345007', 'Ufficio principale'),
(14, 7, 'email', 'info@eta.it', 'Email generale'),
(15, 8, 'telefono', '+390101234508', 'Ufficio principale'),
(16, 8, 'email', 'info@theta.it', 'Email generale'),
(17, 9, 'telefono', '+390912345009', 'Ufficio principale'),
(18, 9, 'email', 'info@iota.it', 'Email generale'),
(19, 10, 'telefono', '+390812345010', 'Ufficio principale'),
(20, 10, 'email', 'info@kappa.it', 'Email generale'),
(21, 11, 'telefono', '+390951234511', 'Ufficio principale'),
(22, 11, 'email', 'info@lambda.it', 'Email generale'),
(23, 12, 'telefono', '+390371234512', 'Ufficio principale'),
(24, 12, 'email', 'info@mu.it', 'Email generale'),
(25, 13, 'telefono', '+390491234513', 'Ufficio principale'),
(26, 13, 'email', 'info@nu.it', 'Email generale'),
(27, 14, 'telefono', '+390401234514', 'Ufficio principale'),
(28, 14, 'email', 'info@xi.it', 'Email generale'),
(29, 15, 'telefono', '+390251234515', 'Ufficio principale'),
(30, 15, 'email', 'info@omicron.it', 'Email generale'),
(31, 16, 'telefono', '+390741234516', 'Ufficio principale'),
(32, 16, 'email', 'info@pi.it', 'Email generale'),
(33, 17, 'telefono', '+390981234517', 'Ufficio principale'),
(34, 17, 'email', 'info@rho.it', 'Email generale'),
(35, 18, 'telefono', '+390591234518', 'Ufficio principale'),
(36, 18, 'email', 'info@sigma.it', 'Email generale'),
(37, 19, 'telefono', '+390571234519', 'Ufficio principale'),
(38, 19, 'email', 'info@tau.it', 'Email generale'),
(39, 20, 'telefono', '+390912345520', 'Ufficio principale'),
(40, 20, 'email', 'info@upsilon.it', 'Email generale'),
(41, 21, 'telefono', '+390731234521', 'Ufficio principale'),
(42, 21, 'email', 'info@phi.it', 'Email generale'),
(43, 22, 'telefono', '+390601234522', 'Ufficio principale'),
(44, 22, 'email', 'info@chi.it', 'Email generale'),
(45, 23, 'telefono', '+390651234523', 'Ufficio principale'),
(46, 23, 'email', 'info@psi.it', 'Email generale'),
(47, 24, 'telefono', '+390611234524', 'Ufficio principale'),
(48, 24, 'email', 'info@omega.it', 'Email generale'),
(49, 25, 'telefono', '+390330185025', 'Ufficio principale'),
(50, 25, 'email', 'info@alphabeta.it', 'Email generale'),
(51, 26, 'telefono', '+390751234526', 'Ufficio principale'),
(52, 26, 'email', 'info@gammadelta.it', 'Email generale'),
(53, 27, 'telefono', '+390721234527', 'Ufficio principale'),
(54, 27, 'email', 'info@epsilonzeta.it', 'Email generale'),
(55, 28, 'telefono', '+390581234528', 'Ufficio principale'),
(56, 28, 'email', 'info@etatheta.it', 'Email generale'),
(57, 29, 'telefono', '+390479000529', 'Ufficio principale'),
(58, 29, 'email', 'info@iotakappa.it', 'Email generale');

-- --------------------------------------------------------

--
-- Struttura della tabella `fornitori`
--

CREATE TABLE `fornitori` (
  `id` int(11) NOT NULL,
  `codice_fornitore` varchar(10) NOT NULL,
  `ragione_sociale` varchar(255) NOT NULL,
  `partita_iva` varchar(20) NOT NULL,
  `codice_fiscale` varchar(20) DEFAULT NULL,
  `stato` enum('attivo','inattivo') DEFAULT 'attivo',
  `data_creazione` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `fornitori`
--

INSERT INTO `fornitori` (`id`, `codice_fornitore`, `ragione_sociale`, `partita_iva`, `codice_fiscale`, `stato`, `data_creazione`) VALUES
(1, 'F001', 'Alfa S.r.l.', '12345678901', 'ABCDEF01G23H456I', 'attivo', '2025-06-13 16:32:46'),
(2, 'F002', 'Beta Spa', '23456789012', 'BCDEFG12H34I567J', 'attivo', '2025-06-13 16:32:46'),
(3, 'F003', 'Gamma SRL', '34567890123', 'CDEFGH23I45J678K', 'attivo', '2025-06-13 16:32:46'),
(4, 'F004', 'Delta Srl', '45678901234', 'DEFGHI34J56K789L', 'attivo', '2025-06-13 16:32:46'),
(5, 'F005', 'Epsilon Spa', '56789012345', 'EFGHIJ45K67L890M', 'attivo', '2025-06-13 16:32:46'),
(6, 'F006', 'Zeta Srl', '67890123456', 'FGHIJK56L78M901N', 'attivo', '2025-06-13 16:32:46'),
(7, 'F007', 'Eta Spa', '78901234567', 'GHIJKL67M89N012O', 'attivo', '2025-06-13 16:32:46'),
(8, 'F008', 'Theta Srl', '89012345678', 'HIJKLM78N90O123P', 'attivo', '2025-06-13 16:32:46'),
(9, 'F009', 'Iota Spa', '90123456789', 'IJKLMN89O01P234Q', 'attivo', '2025-06-13 16:32:46'),
(10, 'F010', 'Kappa Srl', '01234567890', 'JKLMNO90P12Q345R', 'attivo', '2025-06-13 16:32:46'),
(11, 'F011', 'Lambda Spa', '11234567890', 'KLMNOP01Q23R456S', 'attivo', '2025-06-13 16:32:46'),
(12, 'F012', 'Mu Srl', '21234567890', 'LMNOPQ12R34S567T', 'attivo', '2025-06-13 16:32:46'),
(13, 'F013', 'Nu Spa', '31234567890', 'MNOPQR23S45T678U', 'attivo', '2025-06-13 16:32:46'),
(14, 'F014', 'Xi Srl', '41234567890', 'NOPQRS34T56U789V', 'attivo', '2025-06-13 16:32:46'),
(15, 'F015', 'Omicron Spa', '51234567890', 'OPQRST45U67V890W', 'attivo', '2025-06-13 16:32:46'),
(16, 'F016', 'Pi Srl', '61234567890', 'PQRSTU56V78W901X', 'attivo', '2025-06-13 16:32:46'),
(17, 'F017', 'Rho Spa', '71234567890', 'QRSTUV67W89X012Y', 'attivo', '2025-06-13 16:32:46'),
(18, 'F018', 'Sigma Srl', '81234567890', 'RSTUVW78X90Y123Z', 'attivo', '2025-06-13 16:32:46'),
(19, 'F019', 'Tau Spa', '91234567890', 'STUVWX89Y01Z234A', 'attivo', '2025-06-13 16:32:46'),
(20, 'F020', 'Upsilon Srl', '10234567890', 'TUVWXY90Z12A345B', 'attivo', '2025-06-13 16:32:46'),
(21, 'F021', 'Phi Spa', '20234567890', 'UVWXYZ01A23B456C', 'attivo', '2025-06-13 16:32:46'),
(22, 'F022', 'Chi Srl', '30234567890', 'VWXYZA12B34C567D', 'attivo', '2025-06-13 16:32:46'),
(23, 'F023', 'Psi Spa', '40234567890', 'WXYZAB23C45D678E', 'attivo', '2025-06-13 16:32:46'),
(24, 'F024', 'Omega Srl', '50234567890', 'XYZABC34D56E789F', 'attivo', '2025-06-13 16:32:46'),
(25, 'F025', 'Alpha Beta Spa', '60234567890', 'YZABCD45E67F890G', 'attivo', '2025-06-13 16:32:46'),
(26, 'F026', 'Gamma Delta Srl', '70234567890', 'ZABCDE56F78G901H', 'attivo', '2025-06-13 16:32:46'),
(27, 'F027', 'Epsilon Zeta Spa', '80234567890', 'ABCDEZ67G89H012I', 'attivo', '2025-06-13 16:32:46'),
(28, 'F028', 'Eta Theta Srl', '90234567890', 'BCDEFG78H90I123J', 'attivo', '2025-06-13 16:32:46'),
(29, 'F029', 'Iota Kappa Spa', '01234567891', 'CDEFGH89I01J234K', 'attivo', '2025-06-13 16:32:46'),
(30, 'F030', 'Lambda Mu Srl', '11234567891', 'DEFGHI90J12K345L', 'attivo', '2025-06-13 16:32:46');

-- --------------------------------------------------------

--
-- Struttura della tabella `indirizzi`
--

CREATE TABLE `indirizzi` (
  `id` int(11) NOT NULL,
  `via` varchar(255) NOT NULL,
  `cap` varchar(10) NOT NULL,
  `citta` varchar(100) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `paese` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `indirizzi`
--

INSERT INTO `indirizzi` (`id`, `via`, `cap`, `citta`, `provincia`, `paese`) VALUES
(1, 'Borgo Lara 123', '75815', 'San Pier', 'BA', 'Italia'),
(2, 'Canale Valerio 789', '85133', 'Sabbatini sardo', 'SP', 'Italia'),
(3, 'Canale Chiaramonte 7 Appartamento 31', '46986', 'Lara calabro', 'FI', 'Italia'),
(4, 'Piazza Fabrizia 324 Appartamento 99', '64475', 'San Coluccio', 'VB', 'Italia'),
(5, 'Vicolo Veltroni 14 Appartamento 87', '98476', 'Settimo Pina terme', 'TA', 'Italia'),
(6, 'Via Callegari 1', '56742', 'Montalcini sardo', 'ME', 'Italia'),
(7, 'Incrocio Fabbri 094', '04282', 'Borgo Salvi', 'RM', 'Italia'),
(8, 'Viale Modiano 7', '96852', 'Parpinel calabro', 'PZ', 'Italia'),
(9, 'Strada Rocco 229 Appartamento 78', '36548', 'Quarto Marta del friuli', 'PU', 'Italia'),
(10, 'Via Gozzano 594 Appartamento 30', '99551', 'Borgo Silvio', 'FG', 'Italia'),
(11, 'Strada Dallara 941', '26847', 'Gagliano terme', 'BA', 'Italia'),
(12, 'Piazza Bartolomeo 3', '21039', 'Silvia terme', 'TS', 'Italia'),
(13, 'Viale Tagliafierro 8 Piano 8', '04050', 'Segni umbro', 'CT', 'Italia'),
(14, 'Vicolo Flora 2', '70649', 'Quarto Antonietta calabro', 'TV', 'Italia'),
(15, 'Contrada Borsellino 76 Appartamento 52', '43506', 'Samele terme', 'FC', 'Italia'),
(16, 'Borgo Treccani 7', '87794', 'Settimo Pierangelo', 'CS', 'Italia'),
(17, 'Stretto Jacuzzi 5', '87847', 'Spadafora nell\'emilia', 'CA', 'Italia'),
(18, 'Canale Lippomano 5', '31833', 'Quarto Guglielmo lido', 'LO', 'Italia'),
(19, 'Incrocio Gaiatto 9 Appartamento 06', '81569', 'Quarto Lilla umbro', 'VC', 'Italia'),
(20, 'Via Lucarelli 4', '48481', 'Sesto Cipriano calabro', 'SA', 'Italia'),
(21, 'Piazza Gentileschi 9', '90594', 'Borgo Guido', 'MS', 'Italia'),
(22, 'Stretto Martina 66', '58696', 'San Pompeo', 'GE', 'Italia'),
(23, 'Vicolo Giacinto 8', '85447', 'Aporti a mare', 'AL', 'Italia'),
(24, 'Viale Paolucci 443 Appartamento 95', '45078', 'San Enzio veneto', 'VS', 'Italia'),
(25, 'Via Bonino 44 Appartamento 97', '73892', 'Settimo Ottone', 'ME', 'Italia'),
(26, 'Borgo Vincenzo 4', '94201', 'Quarto Lazzaro nell\'emilia', 'VA', 'Italia'),
(27, 'Strada Romana 6', '84719', 'Sesto Ugolino', 'BT', 'Italia'),
(28, 'Via Isa 684 Appartamento 72', '46500', 'Sesto Guido terme', 'SR', 'Italia'),
(29, 'Rotonda Fagotto 4 Piano 5', '01066', 'Elisa a mare', 'LU', 'Italia'),
(30, 'Vicolo Dibiasi 71 Piano 5', '02945', 'San Stefani nell\'emilia', 'TR', 'Italia'),
(31, 'Viale Ostinelli 3 Appartamento 70', '18678', 'Cainero nell\'emilia', 'TN', 'Italia'),
(32, 'Piazza Celentano 3 Appartamento 88', '41711', 'Quarto Licia', 'AP', 'Italia'),
(33, 'Piazza Spanevello 816 Appartamento 10', '69627', 'San Guarino', 'OR', 'Italia'),
(34, 'Viale Pierluigi 93', '73071', 'Quarto Fabrizio', 'VS', 'Italia'),
(35, 'Piazza Giuliano 0', '45361', 'Settimo Carolina terme', 'SA', 'Italia'),
(36, 'Viale Ruggero 49', '52555', 'Borgo Amanda', 'FE', 'Italia'),
(37, 'Viale Aurora 334', '63690', 'Sesto Lodovico', 'BZ', 'Italia'),
(38, 'Stretto Livio 380', '29264', 'Liberto lido', 'CH', 'Italia'),
(39, 'Canale Silvestro 38', '43377', 'Matteo umbro', 'CE', 'Italia'),
(40, 'Viale Calvo 05', '76951', 'Sesto Tonino nell\'emilia', 'AL', 'Italia'),
(41, 'Canale Ruggero 673 Appartamento 35', '37963', 'Nicola laziale', 'SR', 'Italia'),
(42, 'Vicolo Chiesa 992 Piano 6', '73890', 'Ferrabosco nell\'emilia', 'CO', 'Italia'),
(43, 'Canale Martino 990 Appartamento 38', '74357', 'Quarto Alessio', 'BS', 'Italia'),
(44, 'Viale Gianfrancesco 70', '67601', 'Giannuzzi veneto', 'GO', 'Italia'),
(45, 'Contrada Pacetti 8', '28565', 'Guarana calabro', 'EN', 'Italia'),
(46, 'Strada Petrassi 2 Piano 0', '43935', 'Settimo Micheletto', 'SV', 'Italia'),
(47, 'Vicolo Ernesto 2', '35962', 'San Rita a mare', 'FG', 'Italia'),
(48, 'Vicolo Mercantini 943', '99378', 'Vento lido', 'NA', 'Italia'),
(49, 'Viale Fiorino 908 Piano 9', '54797', 'Cicala terme', 'PG', 'Italia'),
(50, 'Borgo Bruno 4 Appartamento 03', '95974', 'Maria del friuli', 'NU', 'Italia'),
(51, 'Via Alfredo Contini 7', '50019', 'Sesto Fiorentino (FI)', 'FI', 'Italia');

-- --------------------------------------------------------

--
-- Struttura della tabella `indirizzi_fornitori`
--

CREATE TABLE `indirizzi_fornitori` (
  `id` int(11) NOT NULL,
  `fornitore_id` int(11) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `indirizzo` varchar(255) NOT NULL,
  `cap` varchar(10) DEFAULT NULL,
  `citta` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `nazione` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `indirizzi_fornitori`
--

INSERT INTO `indirizzi_fornitori` (`id`, `fornitore_id`, `tipo`, `indirizzo`, `cap`, `citta`, `provincia`, `nazione`) VALUES
(1, 1, 'sede legale', 'Via Roma 1', '00100', 'Roma', 'RM', 'Italia'),
(2, 2, 'sede legale', 'Corso Milano 2', '20100', 'Milano', 'MI', 'Italia'),
(3, 3, 'sede legale', 'Piazza Napoli 3', '80100', 'Napoli', 'NA', 'Italia'),
(4, 4, 'sede legale', 'Via Torino 4', '10100', 'Torino', 'TO', 'Italia'),
(5, 5, 'sede legale', 'Via Firenze 5', '50100', 'Firenze', 'FI', 'Italia'),
(6, 6, 'sede legale', 'Via Bologna 6', '40100', 'Bologna', 'BO', 'Italia'),
(7, 7, 'sede legale', 'Via Venezia 7', '30100', 'Venezia', 'VE', 'Italia'),
(8, 8, 'sede legale', 'Via Genova 8', '16100', 'Genova', 'GE', 'Italia'),
(9, 9, 'sede legale', 'Via Palermo 9', '90100', 'Palermo', 'PA', 'Italia'),
(10, 10, 'sede legale', 'Via Bari 10', '70100', 'Bari', 'BA', 'Italia'),
(11, 11, 'sede legale', 'Via Catania 11', '95100', 'Catania', 'CT', 'Italia'),
(12, 12, 'sede legale', 'Via Verona 12', '37100', 'Verona', 'VR', 'Italia'),
(13, 13, 'sede legale', 'Via Padova 13', '35100', 'Padova', 'PD', 'Italia'),
(14, 14, 'sede legale', 'Via Trieste 14', '34100', 'Trieste', 'TS', 'Italia'),
(15, 15, 'sede legale', 'Via Brescia 15', '25100', 'Brescia', 'BS', 'Italia'),
(16, 16, 'sede legale', 'Via Taranto 16', '74100', 'Taranto', 'TA', 'Italia'),
(17, 17, 'sede legale', 'Via Reggio Calabria 17', '89100', 'Reggio Calabria', 'RC', 'Italia'),
(18, 18, 'sede legale', 'Via Modena 18', '41100', 'Modena', 'MO', 'Italia'),
(19, 19, 'sede legale', 'Via Livorno 19', '57100', 'Livorno', 'LI', 'Italia'),
(20, 20, 'sede legale', 'Via Cagliari 20', '09100', 'Cagliari', 'CA', 'Italia'),
(21, 21, 'sede legale', 'Via Lecce 21', '73100', 'Lecce', 'LE', 'Italia'),
(22, 22, 'sede legale', 'Via Ancona 22', '60100', 'Ancona', 'AN', 'Italia'),
(23, 23, 'sede legale', 'Via Pescara 23', '65100', 'Pescara', 'PE', 'Italia'),
(24, 24, 'sede legale', 'Via Perugia 24', '06100', 'Perugia', 'PG', 'Italia'),
(25, 25, 'sede legale', 'Via Tarvisio 25', '33018', 'Tarvisio', 'UD', 'Italia'),
(26, 26, 'sede legale', 'Via Matera 26', '75100', 'Matera', 'MT', 'Italia'),
(27, 27, 'sede legale', 'Via Brindisi 27', '72100', 'Brindisi', 'BR', 'Italia'),
(28, 28, 'sede legale', 'Via Grosseto 28', '58100', 'Grosseto', 'GR', 'Italia'),
(29, 29, 'sede legale', 'Via Rimini 29', '47900', 'Rimini', 'RN', 'Italia');

-- --------------------------------------------------------

--
-- Struttura della tabella `marche`
--

CREATE TABLE `marche` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `marche`
--

INSERT INTO `marche` (`id`, `nome`) VALUES
(1, 'Pandora'),
(2, 'Swarovski'),
(3, 'Bulgari'),
(4, 'Cartier'),
(5, 'Tiffany & Co.'),
(6, 'Chopard'),
(7, 'Damiani'),
(8, 'Breil'),
(9, 'Morellato'),
(10, 'Nomination'),
(11, 'Comete'),
(12, 'Le Bebé'),
(13, 'Bliss'),
(14, 'Calvin Klein Jewelry'),
(15, 'Gucci Jewelry'),
(16, 'Roberto Coin'),
(17, 'Pomellato'),
(18, 'Salvini'),
(19, 'Liu Jo Luxury'),
(20, 'Michael Kors Jewelry');

-- --------------------------------------------------------

--
-- Struttura della tabella `materiali`
--

CREATE TABLE `materiali` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `materiali`
--

INSERT INTO `materiali` (`id`, `nome`) VALUES
(1, 'Oro Giallo'),
(2, 'Oro Bianco'),
(3, 'Oro Rosa'),
(4, 'Argento'),
(5, 'Platino'),
(6, 'Acciaio'),
(7, 'Titanio'),
(8, 'Pelle'),
(9, 'Ceramica'),
(10, 'Bronzo'),
(11, 'Ottone'),
(12, 'Rame'),
(13, 'Resina'),
(14, 'Zirconio'),
(15, 'Legno'),
(16, 'Silicone'),
(17, 'Vetro Murano');

-- --------------------------------------------------------

--
-- Struttura della tabella `pietre`
--

CREATE TABLE `pietre` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `pietre`
--

INSERT INTO `pietre` (`id`, `nome`) VALUES
(1, 'Diamante'),
(2, 'Zaffiro'),
(3, 'Rubino'),
(4, 'Smeraldo'),
(5, 'Topazio'),
(6, 'Ametista'),
(7, 'Acquamarina'),
(8, 'Onice'),
(9, 'Perla'),
(10, 'Quarzo Rosa'),
(11, 'Quarzo Citrino'),
(12, 'Turchese'),
(13, 'Opale'),
(14, 'Granato'),
(15, 'Corallo'),
(16, 'Lapislazzuli'),
(17, 'Zircone'),
(18, 'Cristallo'),
(19, 'Spinello'),
(20, 'Moissanite');

-- --------------------------------------------------------

--
-- Struttura della tabella `stati_articolo`
--

CREATE TABLE `stati_articolo` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `stati_articolo`
--

INSERT INTO `stati_articolo` (`id`, `nome`) VALUES
(1, 'Disponibile'),
(2, 'Non disponibile'),
(3, 'In ordine');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `nome`, `cognome`, `email`, `password`) VALUES
(1, 'Mattia', 'Teriaca', 'mattia.teriaca@villadonatello.it', '$2y$10$MkugTtsE.neCI.yoNoNnXODy2Qu50TZ73zDfDhD18wd8.11VpcDoO');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `articoli`
--
ALTER TABLE `articoli`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `articoli_pietre`
--
ALTER TABLE `articoli_pietre`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `clienti`
--
ALTER TABLE `clienti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codice_fiscale` (`codice_fiscale`);

--
-- Indici per le tabelle `clienti_contatti`
--
ALTER TABLE `clienti_contatti`
  ADD PRIMARY KEY (`cliente_id`),
  ADD KEY `contatto_id` (`contatto_id`);

--
-- Indici per le tabelle `clienti_indirizzi`
--
ALTER TABLE `clienti_indirizzi`
  ADD PRIMARY KEY (`cliente_id`),
  ADD KEY `indirizzo_id` (`indirizzo_id`);

--
-- Indici per le tabelle `configurazione_azienda`
--
ALTER TABLE `configurazione_azienda`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `contatti`
--
ALTER TABLE `contatti`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `contatti_fornitori`
--
ALTER TABLE `contatti_fornitori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fornitore_id` (`fornitore_id`);

--
-- Indici per le tabelle `fornitori`
--
ALTER TABLE `fornitori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codice_fornitore` (`codice_fornitore`),
  ADD UNIQUE KEY `partita_iva` (`partita_iva`);

--
-- Indici per le tabelle `indirizzi`
--
ALTER TABLE `indirizzi`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `indirizzi_fornitori`
--
ALTER TABLE `indirizzi_fornitori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fornitore_id` (`fornitore_id`);

--
-- Indici per le tabelle `marche`
--
ALTER TABLE `marche`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `materiali`
--
ALTER TABLE `materiali`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `pietre`
--
ALTER TABLE `pietre`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `stati_articolo`
--
ALTER TABLE `stati_articolo`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `articoli`
--
ALTER TABLE `articoli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `articoli_pietre`
--
ALTER TABLE `articoli_pietre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `clienti`
--
ALTER TABLE `clienti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT per la tabella `configurazione_azienda`
--
ALTER TABLE `configurazione_azienda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `contatti`
--
ALTER TABLE `contatti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT per la tabella `contatti_fornitori`
--
ALTER TABLE `contatti_fornitori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT per la tabella `fornitori`
--
ALTER TABLE `fornitori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT per la tabella `indirizzi`
--
ALTER TABLE `indirizzi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT per la tabella `indirizzi_fornitori`
--
ALTER TABLE `indirizzi_fornitori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT per la tabella `marche`
--
ALTER TABLE `marche`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT per la tabella `materiali`
--
ALTER TABLE `materiali`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT per la tabella `pietre`
--
ALTER TABLE `pietre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT per la tabella `stati_articolo`
--
ALTER TABLE `stati_articolo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `clienti_contatti`
--
ALTER TABLE `clienti_contatti`
  ADD CONSTRAINT `clienti_contatti_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clienti` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `clienti_contatti_ibfk_2` FOREIGN KEY (`contatto_id`) REFERENCES `contatti` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `clienti_indirizzi`
--
ALTER TABLE `clienti_indirizzi`
  ADD CONSTRAINT `clienti_indirizzi_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clienti` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `clienti_indirizzi_ibfk_2` FOREIGN KEY (`indirizzo_id`) REFERENCES `indirizzi` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `contatti_fornitori`
--
ALTER TABLE `contatti_fornitori`
  ADD CONSTRAINT `contatti_fornitori_ibfk_1` FOREIGN KEY (`fornitore_id`) REFERENCES `fornitori` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `indirizzi_fornitori`
--
ALTER TABLE `indirizzi_fornitori`
  ADD CONSTRAINT `indirizzi_fornitori_ibfk_1` FOREIGN KEY (`fornitore_id`) REFERENCES `fornitori` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
