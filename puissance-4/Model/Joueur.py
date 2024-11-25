from Model.Constantes import *
from Model.Pion import *
from Model.Plateau import *
from random import *



#
# Ce fichier contient les fonctions gérant le joueur
#
# Un joueur sera un dictionnaire avec comme clé :
# - const.COULEUR : la couleur du joueur entre const.ROUGE et const.JAUNE
# - const.PLACER_PION : la fonction lui permettant de placer un pion, None par défaut,
#                       signifiant que le placement passe par l'interface graphique.
# - const.PLATEAU : référence sur le plateau de jeu, nécessaire pour l'IA, None par défaut
# - d'autres constantes nécessaires pour lui permettre de jouer à ajouter par la suite...
#

def type_joueur(joueur: dict) -> bool:
    """
    Détermine si le paramètre peut correspondre à un joueur.

    :param joueur: Paramètre à tester
    :return: True s'il peut correspondre à un joueur, False sinon.
    """
    if type(joueur) != dict:
        return False
    if const.COULEUR not in joueur or joueur[const.COULEUR] not in const.COULEURS:
        return False
    if const.PLACER_PION not in joueur or (joueur[const.PLACER_PION] is not None
            and not callable(joueur[const.PLACER_PION])):
        return False
    if const.PLATEAU not in joueur or (joueur[const.PLATEAU] is not None and
        not type_plateau(joueur[const.PLATEAU])):
        return False
    return True


def construireJoueur(couleur : int) -> dict :
    """

    :param couleur: Entier représentant une couleur
    :return: Dictionnaire représentant un joueur
    :raise TypeError: Si le paramètre n'est pas un entier
    :raise ValueError : Si le paramètre ne correspond pas à une couleur
    """
    if not(type(couleur) == int) :
        raise TypeError("construireJoueur : Le paramètre n’est pas un entier")
    if not(couleur in const.COULEURS) :
        raise ValueError(f"construireJoueur : L’entier donné {couleur} n’est pas une couleur")
    return {const.COULEUR : couleur, const.PLATEAU : None, const.PLACER_PION : None}

def getCouleurJoueur(joueur : dict) -> int :
    """

    :param joueur: Dictionnaire représentant un joueur
    :return: Entier représentant une couleur
    :raise TypeError: Si le paramètre n'est pas un joueur
    """
    if not(type_joueur(joueur)) :
        raise TypeError("« getCouleurJoueur : Le paramètre ne correspond pas à un joueur")
    return joueur[const.COULEUR]

def getPlateauJoueur(joueur : dict) -> list :
    """

    :param joueur: Dictionnaire représentant un joueur
    :return: Liste représentant un plateau
    :raise TypeError: Si le paramètre n'est pas un joueur
    """
    if not(type_joueur(joueur)) :
        raise TypeError("getPlateauJoueur : le paramètre ne correspond pas à un joueur")
    return joueur[const.PLATEAU]

def getPlacerPionJoueur(joueur : dict) -> callable :
    """

    :param joueur: Dictionnaire qui représente un joueur
    :return: Fonction qui représente le placement de pion décidé par l'IA
    :raise TypeError: Si le paramètre n'est pas un joueur
    """
    if not(type_joueur(joueur)) :
        raise TypeError("getPlacerPionJoueur : le paramètre ne correspond pas à un joueur")
    return joueur[const.PLACER_PION]

def getPionJoueur(joueur : dict) -> dict :
    """

    :param joueur: Dictionnaire représentant un joueur
    :return: Dictionnaire représentant un pion
    :raise TypeError: Si le paramètre n'est pas un joueur
    """
    if not(type_joueur(joueur)) :
        raise TypeError("getPionJoueur : Le paramètre ne correspond pas à un joueur")
    return construirePion(getCouleurJoueur(joueur))

def setPlateauJoueur(joueur : dict, plateau : list) -> None :
    """

    :param joueur: Dictionnaire représentant un joueur
    :param plateau: Liste représentant un plateau
    :return: Rien
    :raise TypeError: Si le premier paramètre n'est pas un joueur
    :raise TypeError: Si le deuxième paramètre 'est pas un plateau
    """
    if not(type_joueur(joueur)) :
        raise TypeError("setPlateauJoueur : Le premier paramètre ne correspond pas à un joueur")
    if not(type_plateau(plateau)) :
        raise TypeError("setPlateauJoueur : Le second paramètre ne correspond pas à un plateau ")
    joueur[const.PLATEAU] = plateau
    return None

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
    if getModeEtenduJoueur(joueur) == False :
        nb = randint(0, const.NB_COLUMNS - 1)
        while getPlateauJoueur(joueur)[0][nb] != None :
            nb = randint(0, const.NB_COLUMNS - 1)
    else :
        nb = randint(-const.NB_LINES, const.NB_COLUMNS + const.NB_LINES - 1)
        if 0 <= nb <= const.NB_COLUMNS - 1 :
            while getPlateauJoueur(joueur)[0][nb] != None:
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

def getModeEtenduJoueur(joueur : dict) -> bool :
    """

    :param joueur: Dictionnaire représentant un joueur
    :return:Booléen, True si on est en mode entendu, False sinon
    """
    if not(type_joueur(joueur)) :
        raise TypeError("getModeEtenduJoueur : le paramètre ne correspond pas à un joueur.")
    res = False
    if const.MODE_ETENDU in joueur and joueur[const.MODE_ETENDU] :
        res = True
    return res

def setModeEtenduJoueur(joueur : dict, entendu : bool = True) -> None :
    """

    :param joueur: Dictonnaire représentant un joueur
    :param entendu: Booléen, True si on met le mode entendu, False sinon
    :return:
    """
    if not(type_joueur(joueur)) :
        raise TypeError("setModeEtenduJoueur : le premier paramètre ne correspond pas à un joueur")
    if not(type(entendu) == bool) :
        raise TypeError("setModeEtenduJoueur : le second paramètre ne correspond pas à un booléen")
    if entendu :
        joueur[const.MODE_ETENDU] = True
    else :
        joueur[const.MODE_ETENDU] = False
    return None









