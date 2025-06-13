const logoCache = {}

export async function fetchTeamLogo(teamName) {
    if (!teamName) return null
    if (logoCache[teamName]) return logoCache[teamName]

    const apiKey = '123' // Public demo API key
    const url = `https://www.thesportsdb.com/api/v1/json/123/searchteams.php?t=${encodeURIComponent(teamName)}`

    try {
        const response = await fetch(url)
        if (!response.ok) {
            console.warn(`[Logo] Failed to fetch for ${teamName}: HTTP ${response.status}`)
            return null
        }

        const data = await response.json()
        const team = data?.teams?.[0]
        const logo = team?.strTeamBadge || team?.strLogo || null

        if (!logo) {
            console.warn(`[Logo] No logo found for "${teamName}"`)
        }

        logoCache[teamName] = logo
        return logo
    } catch (error) {
        console.error(`[Logo] Error fetching for "${teamName}":`, error)
        return 'https://r2.thesportsdb.com/images/media/league/fanart/xpwsrw1421853005.jpg/medium'
    }
}
