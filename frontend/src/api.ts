export type Product = {
  code: string
  name: string
  price: number
  formatted_price: string
}

export type BasketTotal = {
  total: number
  formatted: string
}

export async function fetchProducts(): Promise<Product[]> {
  const response = await fetch('/api/products')

  if (!response.ok) {
    throw new Error('Unable to load products.')
  }

  const payload = await response.json()
  return payload.data as Product[]
}

export async function fetchBasketTotal(items: string[]): Promise<BasketTotal> {
  const response = await fetch('/api/basket/total', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
    },
    body: JSON.stringify({ items }),
  })

  if (!response.ok) {
    throw new Error('Unable to calculate basket total.')
  }

  return response.json() as Promise<BasketTotal>
}
