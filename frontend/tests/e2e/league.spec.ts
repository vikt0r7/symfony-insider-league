import { test, expect } from '@playwright/test'

test.describe('Insider Champions League', () => {
    test('should generate season, simulate all weeks, and display final standings', async ({
        page,
    }) => {
        await page.goto('http://localhost:8080')

        // Step 1: Generate new season
        await page.getByRole('button', { name: /generate/i }).click()

        await expect(page.locator('table .club-name')).toContainText([
            'Chelsea',
            'Arsenal',
            'Liverpool',
            'Manchester City',
        ])

        // Step 2: Simiulate all weeks
        for (let i = 0; i < 5; i++) {
            const nextWeekButton = page.getByRole('button', { name: /next week/i })
            if (await nextWeekButton.isDisabled()) break
            await nextWeekButton.click()
            await page.waitForTimeout(500)
        }

        // Step 3: check season end
        await expect(page.locator('.league-finished')).toBeVisible()
        await expect(page.locator('.league-finished')).toContainText('Champion')
    })
})
