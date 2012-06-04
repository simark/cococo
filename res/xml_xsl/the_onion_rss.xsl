<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">	
	<!-- modèle -->
	<xsl:template match="/rss">
		<!-- parcourir les items RSS -->
		<xsl:for-each select="/rss/channel/item">
			<!-- afficher le titre et un lien (la description contient trop de publicité) -->
			<h3><a><xsl:attribute name="href"><xsl:value-of select="link" /></xsl:attribute><xsl:value-of select="title" /></a></h3>
		</xsl:for-each>
		<div class="hspacer"></div>
	</xsl:template>
</xsl:stylesheet>
