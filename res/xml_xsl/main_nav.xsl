<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<!-- paramètres -->
	<xsl:param name="logged" />
	<xsl:param name="selectedkey" />
	
	<!-- modèle -->
	<xsl:template match="/">
		<div id="nav-items">
			<!-- parcours des items du menu selon qu'on est connecté ou non -->
			<xsl:for-each select="menu/item[@logged = $logged]">
				<!-- classe particulière si l'item de menu est celui de la page en cours -->
				<xsl:variable name="addclass">
					<xsl:choose>
						<xsl:when test="@key = $selectedkey">nav-item-selected</xsl:when>
						<xsl:otherwise></xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				
				<!-- item en soi -->
				<span>
					<!-- classe dynamique -->
					<xsl:attribute name="class">nav-item <xsl:copy-of select="$addclass" /></xsl:attribute>
					
					<!-- ne pas mettre de lien si l'item est celui de la page en cours -->
					<xsl:choose>
						<xsl:when test="@key = $selectedkey">
							<!-- valeur du titre dans le document XML -->
							<xsl:value-of select="title" />
						</xsl:when>
						<xsl:otherwise>
							<a>
								<xsl:attribute name="href">?p=<xsl:value-of select="@key" /></xsl:attribute>
								<xsl:value-of select="title" />
							</a>
						</xsl:otherwise>
					</xsl:choose>
				</span>
			</xsl:for-each>
		</div>
	</xsl:template>
</xsl:stylesheet>
