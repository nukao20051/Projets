from Model.Constantes import *
from Model.Plateau import *
from Model.Pion import *
from Model.Joueur import *
from random import randint, choice

plateau = construirePlateau()
for _ in range(20):
    placerPionPlateau(plateau, construirePion(choice(const.COULEURS)), randint(0, const.NB_COLUMNS - 1))

print(plateau)
print("Résultat pions jaune :")
print(detecter4horizontalPlateau(plateau, 0))
print(detecter4verticalPlateau(plateau, 0))
print(detecter4diagonaleDirectePlateau(plateau, 0))
print(detecter4diagonaleIndirectePlateau(plateau, 0))
print("Resultat pions rouge :")
print(detecter4horizontalPlateau(plateau, 1))
print(detecter4verticalPlateau(plateau, 1))
print(detecter4diagonaleDirectePlateau(plateau, 1))
print(detecter4diagonaleIndirectePlateau(plateau, 1))

print(getPionsGagnantsPlateau(plateau))

print("-------------------")
print(isRempliPlateau(plateau))


