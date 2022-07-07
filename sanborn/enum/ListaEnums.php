<?php
defined('CUTTER_DOMAIN_PATH') OR exit('Inclua o arquivo de configução.');

/**
 * classe responsavel por retornar dados dos 
 * enuns da pasta sanborn/enum.
 */
class ListaEnums {
	/**
	 * Retorna o array do enum da letra informada.
	 * @param string $letra
	 * @return array
	 */
	public static function retornaEnumPorLetra($letra) {
		// transformamos em minuscula a letra do nome porque todas as chaves sao minusculas
		$letra = strtolower($letra);
		// pega o caminho do include
		$nome_do_arquivo = self::retornaCaminhoPorLetra($letra);
		// inclui o arquivo com o enum desejado
		include CUTTER_DOMAIN_PATH.'sanborn'.DIRECTORY_SEPARATOR.'enum'.DIRECTORY_SEPARATOR.$nome_do_arquivo.'.php';
		// cria dinamicamente o nome do array com o enum
		$enum = "enum_cutter_{$letra}";
		return $$enum;
	}

	/**
	 * Retorna o caminho fisico do array do enum da letra informada.
	 * @param string $letra
	 * @return string
	 */
	public static function retornaCaminhoPorLetra($letra) {
		if (empty($letra)) throw new CutterException("[ListaEnums](retornaCaminhoPorLetra) A letra '{$letra}' não é valida.", 1);

		$lista_enums = array(
			'a' => 'enum_cutter_a'
			,'b' => 'enum_cutter_b'
			,'c' => 'enum_cutter_c'
			,'d' => 'enum_cutter_d'
			,'e' => 'enum_cutter_e'
			,'f' => 'enum_cutter_f'
			,'g' => 'enum_cutter_g'
			,'h' => 'enum_cutter_h'
			,'i' => 'enum_cutter_i'
			,'j' => 'enum_cutter_j'
			,'k' => 'enum_cutter_k'
			,'l' => 'enum_cutter_l'
			,'m' => 'enum_cutter_m'
			,'n' => 'enum_cutter_n'
			,'o' => 'enum_cutter_o'
			,'p' => 'enum_cutter_p'
			,'q' => 'enum_cutter_q'
			,'r' => 'enum_cutter_r'
			,'s' => 'enum_cutter_s'
			,'t' => 'enum_cutter_t'
			,'u' => 'enum_cutter_u'
			,'v' => 'enum_cutter_v'
			,'w' => 'enum_cutter_w'
			,'x' => 'enum_cutter_x'
			,'y' => 'enum_cutter_y'
			,'z' => 'enum_cutter_z'
		);

		// transformamos em minuscula a letra do nome porque todas as chaves sao minusculas
		$letra = strtolower($letra);
		$path = $lista_enums[$letra];
		if (empty($path)) throw new CutterException("[ListaEnums](retornaCaminhoPorLetra) O path do enum da letra '{$letra}' não foi encontrado.", 1);

		return $lista_enums[$letra];
	}
}