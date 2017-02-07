double s=0.2;
for(occur)
{
  score+= (1 + log(1 + log(tf[i])))/((1 - s) + (s*docLength/docLengthAvg)) * qf[i] * log((docN + 1)/DF[i]);
}