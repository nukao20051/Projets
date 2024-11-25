from Model.Constantes import *
from Model.Pion import *


#
# Le plateau représente la grille où sont placés les pions.
# Il constitue le coeur du jeu car c'est dans ce fichier
# où vont être programmées toutes les règles du jeu.
#
# Un plateau sera simplement une liste de liste.
# Ce sera en fait une liste de lignes.
# Les cases du plateau ne pourront contenir que None ou un pion
#
# Pour améliorer la "rapidité" du programme, il n'y aura aucun test sur les paramètres.
# (mais c'est peut-être déjà trop tard car les tests sont fait en amont, ce qui ralentit le programme...)
#

def type_plateau(plateau: list) -> bool:
    """
    Permet de vérifier que le paramètre correspond à un plateau.
    Renvoie True si c'est le cas, False sinon.

    :param plateau: Objet qu'on veut tester
    :return: True s'il correspond à un plateau, False sinon
    """
    if type(plateau) != list:
        return False
    if len(plateau) != const.NB_LINES:
        return False
    wrong = "Erreur !"
    if next((wrong for line in plateau if type(line) != list or len(line) != const.NB_COLUMNS), True) == wrong:
        return False
    if next((wrong for line in plateau for c in line if not(c is None) and not type_pion(c)), True) == wrong:
        return False
    return True

def construirePlateau() -> list :
    """

    :return: Liste représentant un plateau vide
    """
    plateau = []
    for i in range(const.NB_LINES) :
        line = []
        for j in range(const.NB_COLUMNS) :
            line += [None]
        plateau += [line]
    return plateau

def placerPionPlateau(plateau : list, pion : dict, col_pion : int) -> int :
    """

    :param plateau: Liste repreésentant un plateau
    :param pion: Dictionnaire représentant un pion
    :param col_pion: Entier représnentant la colonne du plateau dans lequel on dépose le pion
    :return: Entier représentant la ligne dans lequel le pion a été déposé
    :raise TypeError: Si le premier paramètre n'est pas un plateau
    :raise TypeError: Si le second paramètre n'est pas un pion
    :raise TypeError: Si le troisième paramètre n'est pas un entier
    :raise ValueError: Si le troisième paramètre ne correspond pas à une colonne du plateau
    """
    if not(type_plateau(plateau) == True) :
        raise TypeError("placerPionPlateau : Le premier paramètre ne correspond pas à un plateau")
    if not(type_pion(pion) == True) :
        raise TypeError("placerPionPlateau : Le second paramètre n’est pas un pion")
    if not(type(col_pion) == int) :
        raise TypeError("placerPionPlateau : Le troisième paramètre n’est pas un entier")
    if not(0 <= col_pion <= const.NB_COLUMNS) :
        raise ValueError(f"placerPionPlateau :  La valeur de la colonne {col_pion} n’est pas correcte")
    ligne_pion = -1
    i = const.NB_LINES - 1
    while ligne_pion == -1 and i >= 0 :
        if plateau[i][col_pion] == None :
            plateau[i][col_pion] = pion
            ligne_pion = i
        i -= 1
    return ligne_pion

def detecter4horizontalPlateau(plateau : list, couleur : int) -> list :
    """

    :param plateau: Liste qui représente un plateau
    :param couleur: Entier qui fait référence à une couleur
    :return: Liste de 4 pions de la même couleur
    :raise TypeError: Si le premier paramètre ne correspond pas à un plateau
    :raise TypError : Si le second paramètre n’est pas un entier
    :raise ValueError : Si le second paramètres ne représente pas une couleur valide
    """
    if not(type_plateau(plateau) == True) :
        raise TypeError("detecter4horizontalPlateau : Le premier paramètre ne correspond pas à un plateau")
    if not(type(couleur) == int) :
        raise TypError("detecter4horizontalPlateau : le second paramètre n’est pas un entier ")
    if not(couleur in const.COULEURS) :
        raise ValueError(f"détecter4horizontalPlateau : La valeur de la couleur {couleur} n’est pas correcte")
    tous_pions_alignes = []
    i = 0
    while i < const.NB_LINES :
        j = 0
        pions_alignes = []
        while j < const.NB_COLUMNS :
            if plateau[i][j] != None and getCouleurPion(plateau[i][j]) == couleur :
                pions_alignes += [plateau[i][j]]
            else :
                pions_alignes = []
            if len(pions_alignes) == 4 :
                tous_pions_alignes += [pions_alignes]
                pions_alignes= []
            j += 1
        i += 1
    return tous_pions_alignes

def detecter4verticalPlateau(plateau : list, couleur : int) -> list :
    """

    :param plateau: Liste qui représente un plateau
    :param couleur: Entier qui fait référence à une couleur
    :return: Liste d'un multiple de 4 pions de la même couleur
    :raise TypeError: Si le premier paramètre ne correspond pas à un plateau
    :raise TypError : Si le second paramètre n’est pas un entier
    :raise ValueError : Si le second paramètres ne représente pas une couleur valide
    """
    tous_pions_alignes = []
    i = 0
    while i < const.NB_COLUMNS:
        j = 0
        pions_alignes = []
        while j < const.NB_LINES:
            if plateau[j][i] != None and getCouleurPion(plateau[j][i]) == couleur:
                pions_alignes += [plateau[j][i]]
            else:
                pions_alignes = []
            if len(pions_alignes) == 4:
                tous_pions_alignes += [pions_alignes]
                pions_alignes = []
            j += 1
        i += 1
    return tous_pions_alignes


def detecter4diagonaleDirectePlateau(plateau : list, couleur : int) -> list :
    """

    :param plateau: Liste qui représente un plateau
    :param couleur: Entier qui représente une couleur
    :return: Liste d'un multiple de 4 pions de la même couleur
    :raise TypeError: Si le premier paramètre ne correspond pas à un plateau
    :raise TypeError: Si le second paramètre n’est pas un entier
    :raise ValueError: Si la valeur de la couleur n’est pas correcte
    """
    if not(type_plateau(plateau)) :
        raise TypeError("detecter4diagonaleIndirectePlateau : Le premier paramètre ne correspond pas à un plateau")
    if not(type(couleur) == int) :
        raise TypeError("detecter4diagonaleIndirectePlateau : Le second paramètre n’est pas un entier")
    if not(couleur in const.COULEURS) :
        raise ValueError(f" detecter4diagonaleIndirectePlateau : La valeur de la couleur {couleur} n’est pas correcte")
    tous_pions_alignes = []
    diagonales = []
    for i in range(-const.NB_LINES + 1, const.NB_COLUMNS):
        diagonale = []
        for j in range(max(0, i), min(const.NB_LINES, i + const.NB_COLUMNS)):
            diagonale.append(plateau[j][j - i])
        if len(diagonale) >= 4 :
            diagonales += [diagonale]
    for i in range(len(diagonales)) :
        pions_alignes = []
        for j in range(len(diagonales[i])) :
            if diagonales[i][j] != None and getCouleurPion(diagonales[i][j]) == couleur :
                pions_alignes += [diagonales[i][j]]
            else :
                pions_alignes = []
            if len(pions_alignes) == 4 :
                tous_pions_alignes += [pions_alignes]
                pions_alignes = []
    return tous_pions_alignes

def detecter4diagonaleIndirectePlateau(plateau : list, couleur : int) -> list :
    """

    :param plateau: Liste qui représente un plateau
    :param couleur: Entier qui représente une couleur
    :return: Liste d'un multiple de 4 pions de la même couleur
    :raise TypeError: Si le premier paramètre ne correspond pas à un plateau
    :raise TypeError: Si le second paramètre n’est pas un entier
    :raise ValueError: Si la valeur de la couleur n’est pas correcte
    """
    if not(type_plateau(plateau)) :
        raise TypeError("detecter4diagonaleIndirectePlateau : Le premier paramètre ne correspond pas à un plateau")
    if not(type(couleur) == int) :
        raise TypeError("detecter4diagonaleIndirectePlateau : Le second paramètre n’est pas un entier")
    if not(couleur in const.COULEURS) :
        raise ValueError(f" detecter4diagonaleIndirectePlateau : La valeur de la couleur {couleur} n’est pas correcte")
    tous_pions_alignes = []
    diagonales = []
    for i in range(const.NB_LINES + const.NB_COLUMNS - 1):
        diagonale = []
        for j in range(max(0, i - const.NB_COLUMNS + 1), min(const.NB_LINES, i + 1)):
            diagonale.append(plateau[j][i - j])
        if len(diagonale) >= 4 :
            diagonales += [diagonale]
    for i in range(len(diagonales)) :
        pions_alignes = []
        for j in range(len(diagonales[i])) :
            if diagonales[i][j] != None and getCouleurPion(diagonales[i][j]) == couleur :
                pions_alignes += [diagonales[i][j]]
            else :
                pions_alignes = []
            if len(pions_alignes) == 4 :
                tous_pions_alignes += [pions_alignes]
                pions_alignes = []
    return tous_pions_alignes

def getPionsGagnantsPlateau(plateau : list) -> list :
    """

    :param plateau: Liste qui représnete un plateau
    :return: Liste des séries de pions gagnantes
    :raise TypeError: Si le paramètre fourni n'est pas un plateau
    """
    if not(type_plateau(plateau)) :
        raise TypeError("getPionsGagnantsPlateau : Le paramètre n’est pas un plateau")
    jaune = [detecter4horizontalPlateau(plateau, const.JAUNE), detecter4verticalPlateau(plateau, const.JAUNE), detecter4diagonaleDirectePlateau(plateau, const.JAUNE), detecter4diagonaleIndirectePlateau(plateau, const.JAUNE)]
    rouge = [detecter4horizontalPlateau(plateau, const.ROUGE), detecter4verticalPlateau(plateau, const.ROUGE), detecter4diagonaleDirectePlateau(plateau, const.ROUGE), detecter4diagonaleIndirectePlateau(plateau, const.ROUGE)]
    res = []
    i = 0
    while i < len(jaune) :
        if jaune[i] == [] :
            del jaune[i]
        else :
            res += jaune[i]
            i += 1
    i = 0
    while i < len(rouge) :
        if rouge[i] == [] :
            del rouge[i]
        else :
            res += rouge[i]
            i += 1
    return res

def isRempliPlateau(plateau : list) -> bool :
    """

    :param plateau: Liste qui représente un plateau
    :return: Booléen, True si le plateau est rempli, False s'il n'est pas rempli
    """
    if not(type_plateau(plateau)) :
        raise TypeError("isRempliPlateau : Le paramètre n'est pas un plateau")
    res = True
    for ligne in plateau :
        for pion in ligne :
            if pion == None :
                res = False
    return res

def placerPionLignePlateau(plateau: list, pion : dict, n_ligne : int, left : bool) -> tuple :
    """
    
    :param plateau: Liste représentant un plateau
    :param pion: Dictionnaire représentant un pion
    :param n_ligne: Entier représentant une ligne
    :param left: Booléen, True si on enfonce le pion par la gauche, sinon, on enfonce le pion par la droite
    :return: Tuple composé de la liste des pions poussés et d'un entier représentant la ligne du dernier pions poussé
    :raise TypeError: Si le premier paramètre n'est pas un plateau
    :raise TypeError: Si le deuxième paramètre n'est pas un pion
    :raise TypeError: Si le trosième paramètre n'est pas un entier
    :raise ValueError: Si le troisème paramètre n'est pas une ligne valide
    :raise TypeError: Si le quatrième paramètre n'est pas un booléen
    """
    if not(type_plateau(plateau)) :
        raise TypeError("placerPionLignePlateau : Le premier paramètre n’est pas un plateau ")
    if not(type_pion(pion)) :
        raise TypeError("placerPionLignePlateau : Le second paramètre n’est pas un pion")
    if not(type(n_ligne) == int) :
        raise TypeError("placerPionLignePlateau : le troisième paramètre n’est pas un entier")
    if not(0 <= n_ligne < const.NB_LINES) :
        raise ValueError(f"placerPionLignePlateau : Le troisième paramètre {n_ligne} ne désigne pas une ligne")
    if not(type(left) == bool) :
        raise TypeError("placerPionLignePlateau : le quatrième paramètre n’est pas un booléen")
    pions_pousses = []
    j = None
    if left :
        if plateau[n_ligne][0] == None :
            plateau[n_ligne][0] = pion
            pions_pousses += plateau[n_ligne][0]
            if plateau[n_ligne + 1][0] == None:
                j = n_ligne + 1
                while j + 1 < const.NB_LINES and plateau[j + 1] == None:
                    j += 1
                tmp = plateau[n_ligne][0]
                plateau[n_ligne][0] = plateau[j][0]
                plateau[j][0] = tmp
        elif None in plateau[n_ligne] :
            fin = plateau[n_ligne].index(None)
            for i in range(fin, 0, -1) :
                tmp = plateau[n_ligne][i]
                plateau[n_ligne][i] = plateau[n_ligne][i-1]
                plateau[n_ligne][i-1] = tmp
            plateau[n_ligne][0] = pion
            for y in range(fin) :
                pions_pousses += [plateau[n_ligne][y]]
            if plateau[n_ligne+1][fin] == None :
                j = n_ligne+1
                while j+1 < const.NB_LINES and plateau[j+1] == None :
                    j += 1
                tmp = plateau[n_ligne][fin]
                plateau[n_ligne][fin] = plateau[j][fin]
                plateau[j][fin] = tmp
        else :
            plateau[n_ligne][const.NB_COLUMNS-1] = None
            for i in range(const.NB_COLUMNS-1, 0, -1) :
                tmp = plateau[n_ligne][i]
                plateau[n_ligne][i] = plateau[n_ligne][i-1]
                plateau[n_ligne][i-1] = tmp
            plateau[n_ligne][0] = pion
            for y in range(const.NB_COLUMNS) :
                pions_pousses += plateau[n_ligne][y]
            j = const.NB_LINES
    else :
        if plateau[n_ligne][const.NB_COLUMNS-1] == None :
            plateau[n_ligne][const.NB_COLUMNS-1] = pion
            pions_pousses += [plateau[n_ligne][const.NB_COLUMNS-1]]
            if plateau[n_ligne+1][const.NB_COLUMNS-1] == None :
                j = n_ligne+1
                while j+1 < const.NB_LINES and plateau[j+1] == None :
                    j += 1
                tmp = plateau[n_ligne][const.NB_COLUMNS-1]
                plateau[n_ligne][const.NB_COLUMNS-1] = plateau[j][const.NB_COLUMNS-1]
                plateau[j][const.NB_COLUMNS-1] = tmp
        elif None in plateau[n_ligne] :
            debut = plateau[n_ligne].index(None)
            for i in range(debut, const.NB_COLUMNS) :
                tmp = plateau[n_ligne][i]
                plateau[n_ligne][i] = plateau[n_ligne][i+1]
                plateau[n_ligne][i+1] = tmp
            plateau[n_ligne][const.NB_COLUMNS] = pion
            for y in range(const.NB_COLUMNS, debut-1, -1) :
                pions_pousses += plateau[n_ligne][y]
            if plateau[n_ligne+1][debut] == None :
                j = n_ligne+1
                while j+1 < const.NB_LINES and plateau[j+1] == None :
                    j += 1
                tmp = plateau[n_ligne][debut]
                plateau[n_ligne][debut] = plateau[j][debut]
                plateau[j][debut] = tmp
        else :
            plateau[n_ligne][0] = None
            for i in range(const.NB_COLUMNS) :
                tmp = plateau[n_ligne][i]
                plateau[n_ligne][i] = plateau[n_ligne][i+1]
                plateau[n_ligne][i+1] = tmp
            plateau[n_ligne][const.NB_COLUMNS-1] = pion
            for y in range(const.NB_COLUMNS-1, -1, -1) :
                pions_pousses += plateau[n_ligne][y]
            j = const.NB_LINES
    return (pions_pousses, j)

def encoderPlateau(plateau : dict) -> str :
    """

    :param plateau: Liste représentant un plateau
    :return: Chaine de caractères représentant le plateau encodé
    :raise TypeError: Si le paramètre n'est pas un plateau
    """
    if not(type_plateau(plateau)) :
        raise TypeError("encoderPlateau : le paramètre ne correspond pas à un plateau")
    chaine = ""
    for ligne in plateau :
        for pion in ligne :
            if pion == None :
                chaine += '_'
            elif getCouleurPion(pion) == 0 :
                chaine += 'J'
            else :
                chaine += 'R'
    return chaine

def isPatPlateau(plateau : list, dico_plateaux : dict) -> bool :
    """

    :param plateau: Liste représentant un plateau
    :param dico_plateaux: Dictionnaire constitué des plateaux déja existants (clés) et de leur occurence (valeurs)
    :return: Booléen, True si un plateau est apparue 5 fois ou plus, False sinon
    :raise TypeError: Si le premier paramètre n'est pas un plateau
    :raise TypeError: Si le deuxième paramètre n'est pas un dictionnaire
    """
    if not(type_plateau(plateau)) :
        raise TypeError("isPatPlateau : Le premier paramètre n’est pas un plateau ")
    if not(type(dico_plateaux) == dict) :
        raise TypeError("isPatPlateau : Le second paramètre n’est pas un dictionnaire ")
    res = False
    encode = encoderPlateau(plateau)
    if encode not in dico_plateaux.keys() :
        dico_plateaux[encode] = 1
    else :
        dico_plateaux[encode] += 1
    if dico_plateaux[encode] >= 5 :
        res = True
    return res








