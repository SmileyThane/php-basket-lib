# PHP basket

## REST API for simple basket actions

Released:
- CRUD for Basket 
- CRUD for Basket Items (predefined and custom)
- Price calculation method

### You can check available API routes in [Postman Collection](https://github.com/SmileyThane/php-basket-lib/blob/main/php-basket-postman-collection.json)
<details>
<summary>Basket</summary>
  
  [GET] `/basket/create`

  [GET] ` /basket/find/{{basket_id}} `

  [DELETE] ` /basket/delete/{{basket_id}} `

</details>

<details>
<summary>Basket Items</summary>
  
  [POST] ` /basket/add-default-item/{{basket_id}} `
  
  [POST] ` /basket/add-custom-item/{{basket_id}} `

  [DELETE] ` /basket/remove-item/{{basket_id}}/{{basket_item_id}} `

</details>

[GET] ` /delivery/calculate/{{basket_id}}/{{show_total_price}} `

## Examples
You can check results in stored samples (`Storage` folder)

| File  | Products | Total |
| ------------- | ------------- | ------------- | 
| 6dff618d31e59eb6229085aab5d0280f3bfb83b755ce7d6bae29500f5914fd76 | [B01, G01] | 37.85 |
| dcc5de2d8ae371ca1904d916f339cbb18ae6ec5f65fa4a744a4039719153d264 | [R01, G01] | 54.37 |
| 5352e8ba9b18a56f07eb57108d027a3e22c999e2548434615cad6d553b7cd682 | [R01, R01] | 60.85 |
| 2bedd09a06391c1feac325de905a467f36d652e6a9cd5f97fc36fb28850d897f | [B01, B01, R01, R01, R01] | 98.27 |
