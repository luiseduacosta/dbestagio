-- phpMyAdmin SQL Dump
-- version 2.9.0.2
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tempo de Geração: Set 13, 2009 as 10:14 PM
-- Versão do Servidor: 5.0.32
-- Versão do PHP: 5.2.0-8+etch15
-- 
-- Banco de Dados: `ess`
-- 

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `afastamentos`
-- 

CREATE TABLE `afastamentos` (
  `id` int(11) NOT NULL auto_increment,
  `carater` enum('total','parcial') NOT NULL,
  `tipo` varchar(35) default NULL,
  `inicio` date default NULL,
  `final` date default NULL,
  `id_professor` int(11) NOT NULL,
  `atadirecao` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `alunos`
-- 

CREATE TABLE `alunos` (
  `id` int(4) NOT NULL auto_increment,
  `nome` varchar(50) character set latin1 NOT NULL default '',
  `registro` int(9) NOT NULL default '0',
  `codigo_telefone` tinyint(2) NOT NULL default '21',
  `telefone` varchar(9) character set latin1 default '0',
  `codigo_celular` tinyint(2) NOT NULL default '21',
  `celular` varchar(9) character set latin1 default '0',
  `email` varchar(50) character set latin1 default NULL,
  `cpf` varchar(12) character set latin1 default NULL,
  `identidade` varchar(15) character set latin1 default NULL,
  `orgao` varchar(10) collate latin1_general_ci NOT NULL,
  `nascimento` date default '0000-00-00',
  `endereco` varchar(50) character set latin1 default NULL,
  `cep` varchar(9) character set latin1 default NULL,
  `municipio` varchar(30) character set latin1 default NULL,
  `bairro` varchar(30) character set latin1 default NULL,
  `observacoes` varchar(250) character set latin1 default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `registro` (`registro`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `alunosNovos`
-- 

CREATE TABLE `alunosNovos` (
  `id` int(4) NOT NULL auto_increment,
  `nome` varchar(50) character set latin1 NOT NULL default '',
  `registro` int(9) NOT NULL default '0',
  `codigo_telefone` tinyint(2) NOT NULL default '21',
  `telefone` varchar(9) character set latin1 default NULL,
  `codigo_celular` tinyint(2) NOT NULL default '21',
  `celular` varchar(9) character set latin1 default NULL,
  `email` varchar(50) character set latin1 default NULL,
  `cpf` varchar(12) character set latin1 NOT NULL default '',
  `identidade` varchar(15) character set latin1 NOT NULL default '',
  `orgao` varchar(10) collate latin1_general_ci NOT NULL,
  `nascimento` date NOT NULL default '0000-00-00',
  `endereco` varchar(50) character set latin1 NOT NULL default '',
  `cep` varchar(9) character set latin1 NOT NULL default '',
  `municipio` varchar(30) character set latin1 NOT NULL default '',
  `bairro` varchar(30) character set latin1 NOT NULL default '',
  `observacoes` varchar(250) character set latin1 default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `registro` (`registro`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `alunos_elearning`
-- 

CREATE TABLE `alunos_elearning` (
  `id` int(11) NOT NULL auto_increment,
  `atutor_id` int(5) NOT NULL,
  `login` varchar(20) NOT NULL,
  `registro` int(9) NOT NULL,
  `cpf` varchar(12) NOT NULL,
  `periodo` varchar(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Tabela ponte com com o atutor';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `alunos_etica`
-- 

CREATE TABLE `alunos_etica` (
  `id` int(11) NOT NULL auto_increment,
  `ordem` tinyint(2) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `registro` int(9) NOT NULL,
  `turno` enum('D','N') NOT NULL,
  `periodo` varchar(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `alunos_ingresso`
-- 

CREATE TABLE `alunos_ingresso` (
  `id` int(11) NOT NULL auto_increment,
  `ordem` tinyint(2) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `registro` int(9) NOT NULL,
  `turno` enum('D','N') NOT NULL,
  `periodo` varchar(6) NOT NULL,
  `etica` varchar(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `areas`
-- 

CREATE TABLE `areas` (
  `numero` smallint(3) NOT NULL auto_increment,
  `area` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`numero`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `areas_estagio`
-- 

CREATE TABLE `areas_estagio` (
  `id` smallint(3) NOT NULL auto_increment,
  `area` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `areasmonografia`
-- 

CREATE TABLE `areasmonografia` (
  `id` int(3) NOT NULL auto_increment,
  `areamonografia` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `bancas`
-- 

CREATE TABLE `bancas` (
  `id` int(11) NOT NULL auto_increment,
  `id_professor` int(4) NOT NULL,
  `tipo` varchar(50) default NULL,
  `funcao` varchar(50) default NULL,
  `data` date default NULL,
  `titulo` varchar(50) default NULL,
  `aluno` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `configuracao`
-- 

CREATE TABLE `configuracao` (
  `id` int(11) NOT NULL auto_increment,
  `mural_periodo_atual` varchar(6) NOT NULL,
  `curso_turma_atual` smallint(2) NOT NULL,
  `curso_encerramento_inscricoes` date NOT NULL,
  `termo_compromisso_periodo` varchar(6) NOT NULL,
  `termo_compromisso_inicio` date NOT NULL,
  `termo_compromisso_final` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `curso_inscricao_instituicao`
-- 

CREATE TABLE `curso_inscricao_instituicao` (
  `id` int(11) NOT NULL auto_increment,
  `id_estagio` int(4) default NULL,
  `area` smallint(3) default NULL,
  `natureza` varchar(30) NOT NULL,
  `instituicao` varchar(75) default NULL,
  `url` varchar(100) NOT NULL,
  `endereco` varchar(105) default NULL,
  `bairro` varchar(30) default NULL,
  `municipio` varchar(30) default NULL,
  `cep` varchar(9) default NULL,
  `telefone` varchar(50) default NULL,
  `fax` varchar(15) default NULL,
  `beneficio` varchar(50) default NULL,
  `fim_de_semana` char(1) default NULL,
  `observacoes` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `curso_inscricao_supervisor`
-- 

CREATE TABLE `curso_inscricao_supervisor` (
  `id` int(11) NOT NULL auto_increment,
  `nome` varchar(70) NOT NULL default '',
  `cpf` varchar(12) NOT NULL default '0',
  `endereco` varchar(100) default NULL,
  `bairro` varchar(30) default NULL,
  `municipio` varchar(30) default NULL,
  `cep` varchar(9) default NULL,
  `codigo_tel` char(2) NOT NULL default '21',
  `telefone` varchar(9) default NULL,
  `codigo_cel` char(2) NOT NULL default '21',
  `celular` varchar(9) default NULL,
  `email` varchar(50) default NULL,
  `escola` varchar(70) default NULL,
  `ano_formatura` varchar(4) default NULL,
  `cress` varchar(10) NOT NULL default '0',
  `regiao` char(2) NOT NULL default '7',
  `outros_estudos` varchar(15) default NULL,
  `area_curso` varchar(40) default NULL,
  `ano_curso` varchar(4) default NULL,
  `cargo` varchar(25) default NULL,
  `num_inscricao` int(3) default NULL,
  `curso_turma` char(1) NOT NULL default '',
  `selecao` tinyint(1) NOT NULL default '0',
  `observacoes` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `curso_inst_super`
-- 

CREATE TABLE `curso_inst_super` (
  `id` int(3) NOT NULL auto_increment,
  `id_instituicao` int(3) default NULL,
  `id_supervisor` int(3) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `dataface__DataGrids`
-- 

CREATE TABLE `dataface__DataGrids` (
  `gridID` int(11) NOT NULL auto_increment,
  `gridName` varchar(64) NOT NULL,
  `gridData` text,
  `tableName` varchar(64) NOT NULL,
  PRIMARY KEY  (`gridID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `dataface__failed_logins`
-- 

CREATE TABLE `dataface__failed_logins` (
  `attempt_id` int(11) NOT NULL auto_increment,
  `ip_address` varchar(32) NOT NULL,
  `username` varchar(32) NOT NULL,
  `time_of_attempt` int(11) NOT NULL,
  PRIMARY KEY  (`attempt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `dataface__preferences`
-- 

CREATE TABLE `dataface__preferences` (
  `pref_id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(64) NOT NULL,
  `table` varchar(128) NOT NULL,
  `record_id` varchar(255) NOT NULL,
  `key` varchar(128) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`pref_id`),
  KEY `username` (`username`),
  KEY `table` (`table`),
  KEY `record_id` (`record_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `dataface__version`
-- 

CREATE TABLE `dataface__version` (
  `version` int(5) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `disciplinas`
-- 

CREATE TABLE `disciplinas` (
  `id` int(11) NOT NULL auto_increment,
  `nivel` varchar(15) default NULL,
  `curriculo` varchar(12) default NULL,
  `codigo` char(6) default NULL,
  `disciplina` varchar(50) default NULL,
  `tipo` varchar(15) default NULL,
  `carga_horaria` smallint(6) default NULL,
  `creditos` tinyint(2) NOT NULL default '0',
  `periodo_noturno` smallint(6) default NULL,
  `periodo_diurno` smallint(6) default NULL,
  `codigo_pre_requisito` char(20) default NULL,
  `codigo_co_requistio` char(15) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `eprofesores`
-- 

CREATE TABLE `eprofesores` (
  `email` varchar(70) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `departamento` varchar(20) NOT NULL,
  `outros` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `essusuarios`
-- 

CREATE TABLE `essusuarios` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(25) NOT NULL default '',
  `senha` varchar(35) NOT NULL default '',
  `uid` smallint(5) NOT NULL default '0',
  `gui` smallint(5) NOT NULL default '0',
  `outros_dados` varchar(250) NOT NULL default '',
  `pasta` varchar(50) NOT NULL default '',
  `shell` varchar(30) default NULL,
  `nome` varchar(50) default NULL,
  `registro` int(9) NOT NULL default '0',
  `setor` enum('funcionario','docente','aluno graduacao','aluno posgraduacao','institucional','sistema','outros') NOT NULL default 'funcionario',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`,`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `estagiarios`
-- 

CREATE TABLE `estagiarios` (
  `id` int(5) NOT NULL auto_increment,
  `id_aluno` smallint(4) NOT NULL default '0',
  `registro` int(9) NOT NULL default '0',
  `turno` char(1) NOT NULL default '',
  `nivel` char(1) NOT NULL default '',
  `tc` smallint(6) NOT NULL default '0',
  `tc_solicitacao` date NOT NULL,
  `id_instituicao` smallint(4) NOT NULL default '0',
  `id_supervisor` smallint(4) default '0',
  `id_professor` smallint(4) NOT NULL default '0',
  `periodo` varchar(6) NOT NULL default '',
  `id_area` tinyint(2) default '0',
  `nota` decimal(4,2) default '0.00',
  `ch` smallint(3) default '0',
  `observacoes` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `estagio`
-- 

CREATE TABLE `estagio` (
  `id` int(4) NOT NULL auto_increment,
  `area` smallint(3) default '0',
  `natureza` varchar(50) NOT NULL,
  `instituicao` varchar(75) NOT NULL default '',
  `url` varchar(100) NOT NULL,
  `endereco` varchar(105) NOT NULL default '',
  `bairro` varchar(30) NOT NULL,
  `municipio` varchar(30) NOT NULL,
  `cep` varchar(9) NOT NULL default '',
  `telefone` varchar(50) NOT NULL default '',
  `fax` varchar(15) NOT NULL default '',
  `beneficio` varchar(50) NOT NULL default '',
  `fim_de_semana` char(1) NOT NULL default '',
  `convenio` int(4) NOT NULL,
  `observacoes` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `eventos`
-- 

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL auto_increment,
  `id_professor` int(11) NOT NULL,
  `data` date default NULL,
  `evento` varchar(50) default NULL,
  `tipo_participacao` varchar(12) default NULL,
  `local` varchar(12) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `historico_professor`
-- 

CREATE TABLE `historico_professor` (
  `id` int(11) NOT NULL auto_increment,
  `id_professor` smallint(6) NOT NULL,
  `datainicio` date NOT NULL,
  `datafim` date NOT NULL,
  `assunto` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `inst_super`
-- 

CREATE TABLE `inst_super` (
  `id` int(4) NOT NULL auto_increment,
  `id_instituicao` smallint(4) NOT NULL default '0',
  `id_supervisor` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `log_supervisores`
-- 

CREATE TABLE `log_supervisores` (
  `id` int(11) NOT NULL auto_increment,
  `id_supervisor` int(11) NOT NULL,
  `cress` int(11) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `data` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `arquivo` varchar(70) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `monografia`
-- 

CREATE TABLE `monografia` (
  `codigo` int(5) NOT NULL auto_increment,
  `catalogo` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL default '',
  `resumo` text NOT NULL,
  `data` date NOT NULL default '0000-00-00',
  `periodo` varchar(6) NOT NULL default '',
  `num_prof` smallint(6) NOT NULL default '0',
  `num_co_orienta` smallint(6) default '0',
  `num_area` smallint(6) NOT NULL default '0',
  `areamonografia` int(4) NOT NULL default '0',
  `url` varchar(15) default NULL,
  PRIMARY KEY  (`codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `mural_estagio`
-- 

CREATE TABLE `mural_estagio` (
  `id` int(3) NOT NULL auto_increment,
  `id_estagio` int(4) default NULL,
  `instituicao` varchar(100) character set latin1 NOT NULL default '',
  `convenio` char(1) character set latin1 NOT NULL default '0',
  `vagas` tinyint(2) NOT NULL default '0',
  `beneficios` varchar(50) character set latin1 default NULL,
  `final_de_semana` char(1) character set latin1 default NULL,
  `cargaHoraria` tinyint(2) NOT NULL default '0',
  `requisitos` varchar(255) character set latin1 default NULL,
  `id_area` tinyint(2) NOT NULL default '0',
  `horario` char(1) character set latin1 default NULL,
  `id_professor` tinyint(3) NOT NULL default '0',
  `dataSelecao` date NOT NULL default '0000-00-00',
  `dataInscricao` date NOT NULL default '0000-00-00',
  `horarioSelecao` varchar(5) character set latin1 default NULL,
  `localSelecao` varchar(70) character set latin1 default NULL,
  `formaSelecao` char(1) character set latin1 default NULL,
  `contato` varchar(70) character set latin1 default NULL,
  `outras` text character set latin1,
  `periodo` varchar(6) character set latin1 default NULL,
  `datafax` date default NULL,
  `email` varchar(70) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `mural_inscricao`
-- 

CREATE TABLE `mural_inscricao` (
  `id` int(11) NOT NULL auto_increment,
  `id_aluno` int(9) NOT NULL default '0',
  `id_instituicao` smallint(3) NOT NULL default '0',
  `data` date NOT NULL default '0000-00-00',
  `periodo` char(6) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `novo_usuarios`
-- 

CREATE TABLE `novo_usuarios` (
  `usuario` varchar(15) NOT NULL default '',
  `senha` varchar(10) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `nucleos_pesquisa`
-- 

CREATE TABLE `nucleos_pesquisa` (
  `id` int(11) NOT NULL auto_increment,
  `sigla` varchar(10) default NULL,
  `nucleo_pesquisa` varchar(150) default NULL,
  `descricao` varchar(255) default NULL,
  `pagina_web` varchar(50) default NULL,
  `diretoriolattes` varchar(15) default NULL,
  `eventos` int(11) default NULL,
  `publicacoes` int(11) default NULL,
  `observacoes` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `orientacoes`
-- 

CREATE TABLE `orientacoes` (
  `id` int(11) NOT NULL auto_increment,
  `id_professor` int(11) NOT NULL,
  `tipo` varchar(12) default NULL,
  `data_inicio` date default NULL,
  `data_fim` date default NULL,
  `data_defesa` date default NULL,
  `titulo` varchar(50) default NULL,
  `aluno` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `prof_area`
-- 

CREATE TABLE `prof_area` (
  `num_prof` smallint(3) NOT NULL default '0',
  `num_area` smallint(3) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `prof_disciplinas`
-- 

CREATE TABLE `prof_disciplinas` (
  `id` int(11) NOT NULL auto_increment,
  `id_disciplina` int(3) default NULL,
  `id_professor` int(3) default NULL,
  `periodo` char(6) default NULL,
  `q_alunos` smallint(3) default NULL,
  `turno` enum('diurno','noturno') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `prof_nucleopesquisa`
-- 

CREATE TABLE `prof_nucleopesquisa` (
  `id` int(11) NOT NULL auto_increment,
  `id_professor` int(11) default NULL,
  `id_nucleopesquisa` int(11) NOT NULL,
  `peso` tinyint(1) NOT NULL default '0',
  `observacoes` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `professores`
-- 

CREATE TABLE `professores` (
  `id` int(3) NOT NULL auto_increment,
  `nome` varchar(50) NOT NULL default '',
  `cpf` char(12) default NULL,
  `siape` mediumint(10) default NULL,
  `datanascimento` date default '0000-00-00',
  `localnascimento` varchar(30) default NULL,
  `sexo` enum('2','1') default '2',
  `telefone` varchar(12) default NULL,
  `celular` varchar(12) default NULL,
  `email` varchar(40) default NULL,
  `homepage` varchar(120) default NULL,
  `redesocial` varchar(50) default NULL,
  `curriculolattes` varchar(50) default NULL,
  `atualizacaolattes` date default '0000-00-00',
  `curriculosigma` varchar(7) default NULL,
  `pesquisadordgp` varchar(20) default NULL,
  `formacaoprofissional` varchar(30) default NULL,
  `universidadedegraduacao` varchar(50) default NULL,
  `anoformacao` mediumint(4) default NULL,
  `mestradoarea` varchar(30) default NULL,
  `mestradouniversidade` varchar(50) default NULL,
  `mestradoanoconclusao` mediumint(4) default NULL,
  `doutoradoarea` varchar(30) default NULL,
  `doutoradouniversidade` varchar(50) default NULL,
  `doutoradoanoconclusao` mediumint(4) default NULL,
  `dataingresso` date NOT NULL default '0000-00-00',
  `formaingresso` varchar(100) default NULL,
  `tipocargo` enum('efetivo','substituto') default 'efetivo',
  `regimetrabalho` enum('40DE','40','20') default NULL,
  `departamento` enum('Fundamentos','Metodos e tecnicas','Politica social') default NULL,
  `dataegresso` date NOT NULL default '0000-00-00',
  `motivoegresso` varchar(100) default NULL,
  `observacoes` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `progressoes`
-- 

CREATE TABLE `progressoes` (
  `id` int(11) NOT NULL default '0',
  `id_professor` int(11) default NULL,
  `tipo` varchar(50) default NULL,
  `data` date default NULL,
  `pontuacao` decimal(10,4) default NULL,
  `avaliacao` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `publicacoes`
-- 

CREATE TABLE `publicacoes` (
  `id` int(11) NOT NULL auto_increment,
  `id_professor` int(11) NOT NULL,
  `data` date default NULL,
  `tipo` varchar(50) default NULL,
  `titulo` varchar(50) default NULL,
  `outros_dados` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `qualificacoes`
-- 

CREATE TABLE `qualificacoes` (
  `id` int(11) NOT NULL auto_increment,
  `id_professor` int(11) NOT NULL,
  `tipo` varchar(50) default NULL,
  `area` varchar(20) default NULL,
  `data_inicio` date default NULL,
  `data_fim` date default NULL,
  `local` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `situacoes`
-- 

CREATE TABLE `situacoes` (
  `id` smallint(2) NOT NULL auto_increment,
  `codigo` smallint(2) NOT NULL default '0',
  `situacao` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `supervisores`
-- 

CREATE TABLE `supervisores` (
  `id` int(4) NOT NULL auto_increment,
  `nome` varchar(70) NOT NULL default '',
  `cpf` varchar(12) NOT NULL default '0',
  `endereco` varchar(100) NOT NULL,
  `bairro` varchar(30) NOT NULL,
  `municipio` varchar(30) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `codigo_tel` char(2) NOT NULL default '21',
  `telefone` varchar(9) NOT NULL default '',
  `codigo_cel` char(2) NOT NULL default '21',
  `celular` varchar(9) NOT NULL default '',
  `email` varchar(50) default NULL,
  `escola` varchar(70) NOT NULL,
  `ano_formatura` varchar(4) NOT NULL,
  `cress` varchar(6) default NULL,
  `regiao` tinyint(2) NOT NULL default '7',
  `outros_estudos` varchar(15) NOT NULL,
  `area_curso` varchar(40) NOT NULL,
  `ano_curso` varchar(4) NOT NULL,
  `cargo` varchar(25) NOT NULL,
  `num_inscricao` int(3) NOT NULL,
  `curso_turma` char(1) NOT NULL,
  `observacoes` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `tcc_alunos`
-- 

CREATE TABLE `tcc_alunos` (
  `numero` int(11) NOT NULL auto_increment,
  `nome` varchar(50) NOT NULL default '',
  `num_monografia` smallint(5) NOT NULL default '0',
  `registro` varchar(10) default NULL,
  PRIMARY KEY  (`numero`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `usuarios`
-- 

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL auto_increment,
  `usuario` varchar(15) NOT NULL default '',
  `senha` varchar(10) NOT NULL default '',
  `role` enum('READ ONLY','NO ACCESS','ADMIN') NOT NULL default 'READ ONLY',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
