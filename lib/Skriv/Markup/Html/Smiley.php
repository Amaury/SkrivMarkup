<?php

namespace Skriv\Markup\Html;

class Smiley {
	static private $_smileysFrom = array(
		':-)',
		':-(',
		':-D',
		':-p',
		':-|',
		';-)',
		':-o',
		':-x',
		":'-(",
		':-@',
		':-*'
	);
	static private $_symbolsFrom = array(
		':sun:',
		':cloud:',
		':umbrella:',
		':star:',
		':phone:',
		':check:',
		':mult:',
		':plus:',
		':skull:',
		':radioactive:',
		':biohazard:',
		':peace:',
		':yinyang:',
		':moon:',
		':square:',
		':circle:',
		':triangle:',
		':arrow:',
		':arrowhead:',
		':bullet:',
		':love:',
		':heart:',
		':spade:',
		':diamond:',
		':club:',
		':note:',
		':recycle:',
		':_1_:', ':_2_:', ':_3_:', ':_4_:', ':_5_:', ':_6_:', ':_7_:', ':_8_:', ':_9_:', ':_10_:',
		':_11_:', ':_12_:', ':_13_:', ':_14_:', ':_15_:', ':_16_:', ':_17_:', ':_18_:', ':_19_:', ':_20_:',
		':dice1:', ':dice2:', ':dice3:', ':dice4:', ':dice5:', ':dice6:',
		':flag:',
		':scale:',
		':atom:',
		':warning:', '/!\\',
		':clock:',
		':command:',
		':hourglass:',
		':enter:',
		':infinity:',
		':_1/2_:', ':_1/3_:', ':_2/3_:', ':_1/4_:', ':_3/4_:', ':_1/5_:', ':_2/5_:', ':_3/5_:', ':_4/5_:',
		':_A_:', ':_B_:', ':_C_:', ':_D_:', ':_E_:', ':_F_:', ':_G_:', ':_H_:', ':_I_:', ':_J_:', ':_K_:',
		':_L_:', ':_M_:', ':_N_:', ':_O_:', ':_P_:', ':_Q_:', ':_R_:', ':_S_:', ':_T_:', ':_U_:', ':_V_:',
		':_W_:', ':_X_:', ':_Y_:', ':_Z_:'
	);
	static private $_smileysTo = array(
		'☺',
		'☹',
		'😃',
		'😋',
		'😐',
		'😉',
		'😲',
		'😶',
		'😥',
		'😠',
		'😘'
	);
	static private $_symbolsTo = array(
		'☀',
		'☁',
		'☂',
		'★',
		'☎',
		'✔',
		'✖',
		'✚',
		'☠',
		'☢',
		'☣',
		'☮',
		'☯',
		'☽',
		'■',
		'●',
		'▲',
		'➔',
		'▶',
		'◉',
		'♥',
		'♥',
		'♠',
		'♦',
		'♣',
		'♩',
		'♻',
		'➊', '➋', '➌', '➍', '➎', '➏', '➐', '➑', '➒', '➓',
		'⓫', '⓬', '⓭', '⓮', '⓯', '⓰', '⓱', '⓲', '⓳', '⓴',
		'⚀', '⚁', '⚂', '⚃', '⚄', '⚅',
		'⚑',
		'⚖',
		'⚛',
		'⚠', '⚠',
		'⌚',
		'⌘',
		'⌛',
		'⎆',
		'∞',
		'½', '⅓', '⅔', '¼', '¾', '⅕', '⅖', '⅗', '⅘',
		'Ⓐ', 'Ⓑ', 'Ⓒ', 'Ⓓ', 'Ⓔ', 'Ⓕ', 'Ⓖ', 'Ⓗ', 'Ⓘ', 'Ⓙ', 'Ⓚ',
		'Ⓛ', 'Ⓜ', 'Ⓝ', 'Ⓞ', 'Ⓟ', 'Ⓠ', 'Ⓡ', 'Ⓢ', 'Ⓣ', 'Ⓤ', 'Ⓥ',
		'Ⓦ', 'Ⓧ', 'Ⓨ', 'Ⓩ'
	);

	static public function convertSmileys($string) {
		return (str_replace(self::$_smileysFrom, self::$_smileysTo, $string));
	}
	static public function convertSymbols($string) {
		return (str_replace(self::$_symbolsFrom, self::$_symbolsTo, $string));
	}
}

