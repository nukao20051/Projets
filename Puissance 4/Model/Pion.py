# Model/Pion.py

from Model.Constantes import *

#
# Ce fichier implémente les données/fonctions concernant le pion
# dans le jeu du Puissance 4
#
# Un pion est caractérisé par :
# - sa couleur (const.ROUGE ou const.JAUNE)
# - un identifiant de type int (pour l'interface graphique)
#
# L'identifiant sera initialisé par défaut à None
#

def type_pion(pion: dict) -> bool:
    """
    Détermine si le paramètre peut être ou non un Pion

    :param pion: Paramètre dont on veut savoir si c'est un Pion ou non
    :return: True si le paramètre correspond à un Pion, False sinon.
    """
    return type(pion) == dict and len(pion) == 2 and const.COULEUR in pion.keys() \
        and const.ID in pion.keys() \
        and pion[const.COULEUR] in const.COULEURS \
        and (pion[const.ID] is None or type(pion[const.ID]) == int)


def construirePion(couleur : int) -> dict :
    """

    :param couleur: Couleur du pion à construire
    :return: Dictionnaire réprésentant un pion
    :raise TypeError: Si le paramètre n'est pas un entier
    :raise ValueError: Si l'entier ne représente pas une couleur
    """
    if not(type(couleur) == int) :
        raise TypeError("construirePion : Le paramètre n’est pas de type entier")
    if not(couleur in const.COULEURS) :
        raise ValueError(f"construirePion : la couleur {couleur} n’est pas correcte")
    return {const.COULEUR : couleur, const.ID : None}

def getCouleurPion(pion : dict) -> int :
    """

    :param pion: Dictionnaire représentant un pion
    :return: entier représentant la couleur du pion
    :raise TypeError: Si le paramètre n'est pas un pion
    """
    if not type_pion(pion):
        raise TypeError("getCouleurPion : Le paramètre n’est pas un pion")
    return pion[const.COULEUR]

def setCouleurPion(pion : dict, n_couleur : int) -> None :
    """

    :param pion: Dictionnaire représentant un pion
    :param n_couleur: Entier représentant une couleur
    :return: Rien
    :raise TypeError: Si le premier paramètre n'est pas un pion
    :raise TypeError: Si le deuxième paramètre n'est pas un entier
    :raise ValueError: Si le dexuième paramètre ne correspond à aucune couleur
    """
    if not type_pion(pion):
        raise TypeError("setCouleurPion : le premier paramètre n'est pas un pion")
    if not(type(n_couleur) == int) :
        raise TypeError(" setCouleurPion : Le second paramètre n’est pas un entier")
    if not(n_couleur in const.COULEURS) :
        raise ValueError(f"setCouleurPion : Le second paramètre {n_couleur} n’est pas une couleur")
    pion[const.COULEUR] = n_couleur
    return None

def getIdPion(pion : dict) -> int :
    """

    :param pion: Dictionnaire représentant un pion
    :return: Entier représnetant l'identifiant du pion'
    :raise TypeError: Si le paramètre n'est pas un pion
    """
    if not type_pion(pion):
        raise TypeError("getIdPion : le paramètre n'est pas un pion")
    return pion[const.ID]

def setIdPion(pion : dict, n_id : int) -> None :
    """

    :param pion: Dictionnaire représentant un pion
    :param n_id: Entier représnetant un identifiant
    :return: Rien
    :raise TypeError: Si le premier paramètre n'est pas un pion
    :raise TypeError: Si le deuxième paramètre n'est pas un entier
    """
    if type_pion(pion) == False :
        raise TypeError("setIDPion : Le premier paramètre n'est pas un pion")
    if not(type(n_id) == int) :
        raise TypeError("setIdPion : Le second paramètre n’est pas un entier")
    pion[const.ID] = n_id

