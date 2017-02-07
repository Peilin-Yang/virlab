double okapiK1 = 1.2;
double okapiB=0.4;
double okapiK3 = 1000;
for(occur)
{
        double idf = log((docN-DF[i]+0.5)/(DF[i]+0.5));
        double weight = ((okapiK1+1.0)*tf[i]) / (okapiK1*(1.0-okapiB+okapiB*docLength/docLengthAvg)+tf[i]);
        double tWeight = ((okapiK3+1)*qf[i])/(okapiK3+qf[i]);
        score+=idf*weight*tWeight;
}