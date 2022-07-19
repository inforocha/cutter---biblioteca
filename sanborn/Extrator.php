<?php
defined('CUTTER_DOMAIN_PATH') OR exit('Inclua o arquivo de configução.');

/**
 * classe responsavel por retornar dados dos 
 * enuns da pasta sanborn/enum.
 */
class Extrator {
	/**
	 * Retorna o cutter baseado em um enum.
	 * @param array $enum
	 * @param string $autor
	 * @return string
	 */
	public static function extrair($enum, $autor, $primeira_letra_autor) {
		if (empty($autor)) throw new CutterException("[Extrator](extrair) Autor não pode ser vazio.", 1);

		// aqui recebemos a string com a chave do array enum
		$chave = self::extrair_chave($enum, $autor);
		// pegamos o numero do cutter baseado na chave
		if (!isset($enum[$chave])) throw new CutterException("[Extrator](extrair) A chave '{$chave}' não foi encontrada.", 1);
		$numero_cutter = $enum[$chave];
		// agora criamos o cutter usando a primeira letra em maiuscula.
		$cutter = strtoupper($primeira_letra_autor).$numero_cutter;

		return $cutter;
	}

	/**
	 * Existem alguns ajustes que devem ser feitos.
	 * As regras a serem usadas no ajuste estao comentadas.
	 * @param string $autor
	 * @return string
	 */
	public static function ajustar_nome_autor($autor) {
		// reduzindo o nome do autor a 16 caracteres, caso encontre uma chame de enum maior que 16 caracteres favor alterar este valor
		$autor = substr($autor, 0, 16);

		// autor eh uma pessoa juridica. Segue a regra de nome normal. ex: Concurso Nacional de Cronicas, Premio Luiz Fernando Verissimo da Associacao Bamerindus.
		// esta regra eh ajustada na proxima

		// o autor sendo uma frase. segue regra de nome normal tirando os atigos. exemplo de livro: Oração mental segundo Santa Teresa por Um Carmelita Descalco, coordenado por Patricio Sciadini, traduzido e adaptacao por Geraldo Mariano Junior
		$quebrado = explode(' ', $autor);
		$novo_autor = array();
		foreach ($quebrado as $parte) {
			// removendo os artigos e o por
			if (in_array(strtolower($parte), array('a','as','o', 'os', 'um', 'uns', 'uma', 'umas', 'do', 'dos', 'da', 'das', 'por'))) {
				continue;
			}
			$novo_autor[] = $parte;
		}
		// remontando o autor com um espaco entre os nomes
		$autor = implode(' ', $novo_autor);

		// sobrenomes com contracao Mc, M'c, M ' equivalem a Mac. McPherson é 'Mac', M’c Donald’s é 'Mac'.
		$autor = str_replace("Mc", 'Mac', $autor);
		$autor = str_replace("M'c", 'Mac', $autor);
		$autor = str_replace("M '", 'Mac', $autor);

		// sobrenome com grau de parentesco: a notação de autor é feita pelo sobrenome que antecede ao grau de parentesco: 
		// Richard P. Momsen Jr., Manoel Gonçalves Ferreira Filho, David Jardim Júnior,Amaury Júnior, Osório Silva Barbosa Sobrinho, etc.
		// @todo - ainda pensando como fazer

		// sobrenome com prefixo: somente para prefixo que fazem parte do nome. para François d’Arcy desconsideramos o d' e para François D’Arcy consideramos o D e desconsideramos o apostrofo
		$prefixos = array(
			"a'", "b'", "c'", "d'", "e'", "f'", "g'", "h'", "i'", "j'", "k'", "l'", "m'", 
			"n'", "o'", "p'", "q'", "r'", "s'", "t'", "u'", "v'", "x'", "w'", "y'", "'z"
		);
		$duas_primeiras_letras = substr($autor, 0, 2);
		if (in_array($duas_primeiras_letras, $prefixos)) {
			$autor = str_replace($duas_primeiras_letras, '', $autor);
		}

		// sobrenome com hífen ou traco: notacao de autor para a primeira palavra que compoe a hifenizacao. exe: Antoine de Saint-Exupery é 'SaintExupery'
		$autor = str_replace("-", '', $autor);


		// quando tiver apostrofo no nome desconsidera-lo. exemplo o'hara eh 'ohara'. Colocamos por ultimo pq existem regras que abrangem apostrofo
		$autor = str_replace("'", '', $autor);

		return $autor;
	}

	/**
	 * Retorna a chave do cutter.
	 * @param array $enum
	 * @param string $autor
	 * @return string
	 */
	public static function extrair_chave($enum, $autor) {

		// pegamos somente as descricoes do enum para usa-las na omparacao.
		$autores = array_keys($enum);
		$chave_cutter = '';
		// comparando o nome do autor com todos os nomes do enum para pegar uma correspondencia.
		foreach ($autores as $value) {
			// transformamos em minusculas para evitar erros de comparação caso o usuario informe o autor diferente do padrao.
			$autor_enum_minusculo = strtolower($value);
			$autor_obra_minusculo = strtolower($autor);
			// aqui verifico com e sem o ponto porque nao sei se o cliente ira consultar utilizando o ponto no nome.
			// ex: 'Robinson, D.' e 'Robinson, D' serao considerados iguais
			if ($autor_enum_minusculo == $autor_obra_minusculo || "{$autor_enum_minusculo}." == $autor_obra_minusculo) {
				$chave_cutter = $value;
			}
		}

		// removemos o ultimo caractere da string para caso ela tenha de ser verificada novamente com a recursao
		$autor = substr($autor, 0, -1);
		if (empty($chave_cutter) && !empty($autor)) {
			// nesse caso ainda nao temos o cutter, entao usamos a recursao para tentar novamente
			$chave_cutter = self::extrair_chave($enum, $autor);
		}

		// aqui ou temos o cutter ou nao teve nenhuma correspondencia.
		return $chave_cutter;
	}
}