# Price Calculator

### Команды для тестирования

Расчет цены

```sh
$ curl --header "Content-Type: application/json" \
--request POST \
--data '{"product": 1, "taxNumber": "DE123456789", "couponCode": "P10-D90981570D"}' \
http://127.0.0.1:8000/calculate-price
```

Проведение покупки

```sh
$ curl --header "Content-Type: application/json" \
--request POST \
--data '{"product": 1, "taxNumber": "DE123456789", "couponCode": "P10-D90981570D"}' \
http://127.0.0.1:8000/calculate-price
```

