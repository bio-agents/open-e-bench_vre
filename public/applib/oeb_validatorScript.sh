#!/bin/bash
 
# print it 
agentJSON="$1"
validator="$2"
cd "../../../oeb_agent_validation/fairtracks_validator/python"
source ".py3env/bin/activate"
exec python3.6 fairGTrackJsonValidate.py OpEB-VRE-schemas/"$validator" "${agentJSON}"

