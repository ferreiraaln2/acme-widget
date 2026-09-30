import { expect, test, type Page } from '@playwright/test'

async function addByCode(page: Page, code: string, times = 1) {
  const row = page.locator('.product-row', { hasText: code })
  for (let i = 0; i < times; i++) {
    await row.getByRole('button', { name: 'Add' }).click()
  }
}

async function expectTotal(page: Page, total: string) {
  await expect(page.getByTestId('basket-total')).toContainText(total)
}

test.beforeEach(async ({ page }) => {
  await page.goto('/')
  await expect(page.getByRole('heading', { name: 'Catalogue' })).toBeVisible()
  await expect(page.getByText('Blue Widget')).toBeVisible()
})

test('B01 + G01 totals $37.85', async ({ page }) => {
  await addByCode(page, 'B01')
  await addByCode(page, 'G01')
  await expectTotal(page, '$37.85')
})

test('R01 + R01 totals $54.37', async ({ page }) => {
  await addByCode(page, 'R01', 2)
  await expectTotal(page, '$54.37')
})

test('R01 + G01 totals $60.85', async ({ page }) => {
  await addByCode(page, 'R01')
  await addByCode(page, 'G01')
  await expectTotal(page, '$60.85')
})

test('B01 x2 + R01 x3 totals $98.27', async ({ page }) => {
  await addByCode(page, 'B01', 2)
  await addByCode(page, 'R01', 3)
  await expectTotal(page, '$98.27')
})
