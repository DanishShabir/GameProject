#!/bin/bash
echo "#"
echo "# Building the lora-oil-app container"
echo "#"
docker-compose -f docker-compose.prod.yaml up --build -d


echo "#"
echo "# re-tag it as a release locally as doodlelabsuk/lora-oil-app:v1"
echo "#"
#docker tag lora-oil-app:latest doodlelabsuk/lora-oil-app:v1

echo "#"
echo "# push the new tagged container to dockerhub"
echo "#"
#docker push doodlelabsuk/lora-oil-app:v1

echo "###############################################################"
echo "# DONE - run 'docker-compose -f run-prod up -d on production' #"
echo "###############################################################"
