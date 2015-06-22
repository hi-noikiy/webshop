<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:outline="http://wkhtmltopdf.org/outline"
                xmlns="http://www.w3.org/1999/xhtml">
  <xsl:output doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
              doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
              indent="yes" />
  <xsl:template match="outline:outline">
    <html>
      <head>
        <title>Inhoudsopgave</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style>
          body {
            width: 50%;
            margin: 0 auto;
            font-family: 'Titillium Web', sans-serif;
          }

          h1 {
            text-align: center;
            font-size: 15px;
          }

          div {
            border-bottom: 1px dashed rgb(200,200,200);
          }
          
          span {
            float: right;
          }

          li {
            list-style: none;
          }
          
          ul {
            font-size: 12px;
          }

          ul ul {font-size: 80%; }
          ul {padding-left: 0em;}
          ul ul {padding-left: 1em;}
          a {text-decoration:none; color: black;}
        </style>
      </head>
      <body>
        <h1>Inhoudsopgave</h1>
        <ul><xsl:apply-templates select="outline:item/outline:item"/></ul>
      </body>
    </html>
  </xsl:template>

  <xsl:template match="outline:item">
    <xsl:variable name="currentletter" select="''" />
    <xsl:variable name="lastletter" select="''" />

    <xsl:if test="name(../..) = 'outline'">
      <xsl:variable name="currentletter" select="substring(./@title,1,1)" />
    </xsl:if>

    <xsl:if test="not(lastletter = currentletter) and name(../..) = 'outline'">
      <p><xsl:value-of select="currentletter" /></p>
    </xsl:if>

    <li>
      <xsl:if test="@title!=''">
        <div>
          <a>
            <xsl:if test="@link">
              <xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
            </xsl:if>
            <xsl:if test="@backLink">
              <xsl:attribute name="name"><xsl:value-of select="@backLink"/></xsl:attribute>
            </xsl:if>
            <xsl:value-of select="@title" /> 
          </a>
          <span> <xsl:value-of select="@page" /> </span>
        </div>
      </xsl:if>

      <xsl:if test="name(../..) = 'outline'">
        <xsl:variable name="lastletter" select="currentletter" />
      </xsl:if>

      <ul>
        <xsl:comment>added to prevent self-closing tags in QtXmlPatterns</xsl:comment>
        <xsl:apply-templates select="outline:item"/>
      </ul>
    </li>
  </xsl:template>
</xsl:stylesheet>
