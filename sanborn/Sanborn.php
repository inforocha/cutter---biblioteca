<?php
defined('CUTTER_DOMAIN_PATH') OR exit('Inclua o arquivo de configução.');

if (!class_exists('CutterException')) {
	include CUTTER_DOMAIN_PATH.'CutterException.php';
}
if (!class_exists('ListaEnums')) {
	include CUTTER_DOMAIN_PATH.DIRECTORY_SEPARATOR.'sanborn'.DIRECTORY_SEPARATOR.'enum'.DIRECTORY_SEPARATOR.'ListaEnums.php';
}
if (!class_exists('Extrator')) {
	include CUTTER_DOMAIN_PATH.DIRECTORY_SEPARATOR.'sanborn'.DIRECTORY_SEPARATOR.'Extrator.php';
}
if (!class_exists('NumerosPorExtenso')) {
	include CUTTER_DOMAIN_PATH.DIRECTORY_SEPARATOR.'NumerosPorExtenso.php';
}

/**
 * 
 */
class Sanborn /*extends AnotherClass*/ {
	private $autor = '';
	private $titulo_obra = '';
	private $configuracao = array(
		'usar_titulo_obra' => false
	);

	function __construct() {
		# code...
	}

	/**
	 * Padrao do retorno da geracao
	 * @param boolean $sucesso - true(sucesso), false(erro)
	 * @param string $msg
	 * @return array
	 */
	private function retorno($sucesso, $msg) {
		return array(
			'sucesso' => $sucesso,
			'msg' => $msg 
		);
	} 

	/**
	 * responsavel para gerar a string do cutter
	 * @return array
	 */
	public function gerar() {
		try {
			// pega o autor da obra
			$autor = trim($this->autor);
			if (empty($autor)) throw new CutterException("[Sanborn](gerar) Autor não pode ser vazio.", 1);


			$autor = Extrator::ajustar_nome_autor($autor);
			if (empty($autor)) throw new CutterException("[Sanborn](gerar) Autor não pode ser vazio após regras serem aplicadas.", 1);
			$primeira_letra_autor = $autor[0];
			if (empty($primeira_letra_autor)) throw new CutterException("[Sanborn](gerar) Erro ao identificar a priemira letra do sobrenome do Autor.", 1);

			$enum = ListaEnums::retornaEnumPorLetra($primeira_letra_autor);

			// guardando somente os autores para percorrer e verificar qual o correspondente
			$cutter = Extrator::extrair($enum, $autor, $primeira_letra_autor);
			if (empty($cutter)) throw new CutterException("[Sanborn](gerar) cutter de {$autor} não foi encontrado.", 1);

			if ($this->configuracao['usar_titulo_obra']) {
				// quando iniciar com espaco em branco
				$titulo = trim($this->titulo_obra);

				// quando iniciar por numero deve trocar por descricao do numero
				$primeiro_numero_aluno = $titulo[0];
				if (in_array($primeiro_numero_aluno, array(1,2,3,4,5,6,7,8,9))) {
					$titulo[0] = NumerosPorExtenso::converter($primeiro_numero_aluno);
				}

				if ($primeiro_numero_aluno === '0') {
					$titulo[0] = NumerosPorExtenso::converter($primeiro_numero_aluno);
				}

				// removendo artigos do titulo
				$quebrado = explode(' ', $titulo);
				$novo_titulo = array();
				foreach ($quebrado as $parte) {
					// removendo os artigos e o por
					if (in_array(strtolower($parte), array('a','as','o', 'os', 'um', 'uns', 'uma', 'umas', 'do', 'dos', 'da', 'das', 'por'))) {
						continue;
					}
					$novo_titulo[] = $parte;
				}
				// remontando o autor com um espaco entre os nomes
				$titulo = implode(' ', $novo_titulo);

				// novamente retiramos os espacos em branco para o caso de alguma transformação anterior gerar um campo em branco no inicio.
				$titulo = trim($titulo);

				// a letra do titulo deve ficar em minusculo
				$cutter .= strtolower($titulo[0]);
			}

			return $this->retorno(true, $cutter);
		} catch (CutterException $e) {
			return $this->retorno(false, $e->getMessage());
		} catch (Exception $e) {
			return $this->retorno(false, 'Ocorreu um erro inesperado ao tentar gerar o Cutter!');
		}

	}

	/**
	 * adiciona o autor utilizado para gerar o cutter.
	 * formato do nome do autor: Sobrenome, Nome e complemento do nome
	 * 
	 * @return string
	 */
	public function setAutor($autor) {
		$this->autor = $autor;
	}

	/**
	 * adiciona o titulo da obra utilizado para gerar o cutter.
	 * 
	 * @return string
	 */
	public function setTituloObra($titulo_obra) {
		$this->titulo_obra = $titulo_obra;
	}

	/**
	 * adiciona a configuracao utilizada para gerar o cutter.
	 * 
	 * @return string
	 */
	public function setConfiguracao($config) {
		if (isset($config['usar_titulo_obra'])) $this->configuracao['usar_titulo_obra'] = $config['usar_titulo_obra']; 
	}
}