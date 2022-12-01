#!/bin/bash

bin/magento maas:import:product

bin/magento maas:import:offer

bin/magento maas:import:seller

sleep 15m

bin/magento maas:update:bestoffer
