from Model.Pion import *
from Model.Joueur import *
from Model.Plateau import *
from Model.Constantes import *

def setPlacerPionJoueur(joueur : dict, fonction : callable) -> None :
    """

    :param joueur: Dictionnaire représentant un joueur
    :param fonction: Fonction qui représente le placement de pion décidé par l'IA
    :return: Rien
    :raise TypeError: Si le premier paramètre n'est pas un joueur
    :raise TypeError: Si le deuxième paramètre n'est pas une fonction
    """
    if not(type_joueur(joueur)) :
        raise TypeError("setPlacerPionJoueur : Le premier paramètre ne correspond pas à un joueur")
    if not(callable(fonction)) :
        raise TypeError("setPlacerPionJoueur : le second paramètre n’est pas une fonction")
    joueur[const.PLACER_PION] = fonction
    return None

def _placerPionJoueur(joueur : dict) -> int :
    """

    :param joueur: Dictionnaire qui représente un joueur
    :return: Entier représentant la colonne dans lequel l'IA lache le pion
    """
    nb = randint(0, const.NB_COLUMNS - 1)
    while getPlateauJoueur(joueur)[0][nb] != None :
        nb = randint(0, const.NB_COLUMNS - 1)
    return nb

def initialiserIAJoueur(joueur : dict, premier : bool) -> None :
    """

    :param joueur: Dictionnaire qui représente un joueur
    :param premier: Booléen, True si le joueur commence en premier, False si ce n'est pas le cas
    :return: Rien
    :raise TypeError: Si le premier paramètre n'est pas un joueur
    :raise TypeError: Si le deuxième paramètre n'est pas un booléen
    """
    if not(type_joueur(joueur)) :
        raise TypeError("initialiserIAJoueur : Le premier paramètre n’est pas un joueur")
    if not(type(premier) == bool) :
        raise TypeError("initialiserIAJoueur : Le second paramètre n’est pas un booléen")
    setPlacerPionJoueur(joueur, _placerPionJoueur)
    return None

