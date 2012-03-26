<?php
/*
mimetypes.php -  Exension to MIME-Type mapping, pretty much the same as mime.types from Apache
Copyright (C) 2002, 2003, 2004 Stephen Lawrence Jr., Khoa Nguyen
Copyright (C) 2007 Stephen Lawrence Jr., Jon Miner
Copyright (C) 2008-2011 Stephen Lawrence Jr.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

// Read default MIME Types from System
global $mimetypes;
$mimetypes = array(
'ai'        => 'application/postscript',
'aif'       => 'audio/x-aiff',
'aifc'      => 'audio/x-aiff',
'aiff'      => 'audio/x-aiff',
'asc'       => 'text/plain',
'asf'       => 'video/x-ms-asf',
'asx'       => 'video/x-ms-asf',
'au'        => 'audio/basic',
'avi'       => 'video/x-msvideo',
'bcpio'     => 'application/x-bcpio',
'bin'       => 'application/octet-stream',
'bmp'       => 'image/bmp',
'book'      => 'application/x-maker',
'bz2'       => 'application/x-bzip2',
'c'         => 'text/plain',
'cc'        => 'text/plain',
'cdf'       => 'application/x-netcdf',
'chrt'      => 'application/x-kchart',
'class'     => 'application/octet-stream',
'com'       => 'application/x-msdos-program',
'cpio'      => 'application/x-cpio',
'cpp'       => 'text/plain',
'cpt'       => 'application/mac-compactpro',
'csh'       => 'application/x-csh',
'css'       => 'text/css',
'dcr'       => 'application/x-director',
'deb'       => 'application/x-debian-package',
'dir'       => 'application/x-director',
'djv'       => 'image/vnd.djvu',
'djvu'      => 'image/vnd.djvu',
'dl'        => 'video/dl',
'dll'       => 'application/octet-stream',
'dms'       => 'application/octet-stream',
'doc'       => 'application/msword',
'docm'      => 'application/vnd.ms-word.document.macroEnabled.12',
'docx'      => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
'dot'       => 'application/msword',
'dotm'      => 'application/vnd.ms-word.template.macroEnabled.12',
'dotx'      => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
'dtd'       => 'text/xml',
'dvi'       => 'application/x-dvi',
'dxr'       => 'application/x-director',
'eps'       => 'application/postscript',
'etx'       => 'text/x-setext',
'exe'       => 'application/x-msdos-program',
'ez'        => 'application/andrew-inset',
'fb'        => 'application/x-maker',
'fbdoc'     => 'application/x-maker',
'fli'       => 'video/fli',
'flv'       => 'video/x-flv',
'fm'        => 'application/x-maker',
'frame'     => 'application/x-maker',
'frm'       => 'application/x-maker',
'gif'       => 'image/gif',
'gl'        => 'video/gl',
'gtar'      => 'application/x-gtar',
'gz'        => 'application/x-gzip',
'h'         => 'text/plain',
'hdf'       => 'application/x-hdf',
'hh'        => 'text/plain',
'hpp'       => 'text/plain',
'hqx'       => 'application/mac-binhex40',
'htm'       => 'text/html',
'html'      => 'text/html',
'ice'       => 'x-conference/x-cooltalk',
'ief'       => 'image/ief',
'iges'      => 'model/iges',
'igs'       => 'model/iges',
'img'       => 'application/octet-stream',
'iso'       => 'application/octet-stream',
'jad'       => 'text/vnd.sun.j2me.app-descriptor',
'jar'       => 'application/x-java-archive',
'java'      => 'text/plain',
'jnlp'      => 'application/x-java-jnlp-file',
'jpe'       => 'image/jpeg',
'jpeg'      => 'image/jpeg',
'jpg'       => 'image/jpeg',
'js'        => 'application/x-javascript',
'kar'       => 'audio/midi',
'kil'       => 'application/x-killustrator',
'kpr'       => 'application/x-kpresenter',
'kpt'       => 'application/x-kpresenter',
'ksp'       => 'application/x-kspread',
'kwd'       => 'application/x-kword',
'kwt'       => 'application/x-kword',
'latex'     => 'application/x-latex',
'lha'       => 'application/octet-stream',
'lzh'       => 'application/octet-stream',
'm3u'       => 'audio/x-mpegurl',
'maker'     => 'application/x-maker',
'man'       => 'application/x-troff-man',
'me'        => 'application/x-troff-me',
'mesh'      => 'model/mesh',
'mid'       => 'audio/midi',
'midi'      => 'audio/midi',
'mif'       => 'application/vnd.mif',
'mov'       => 'video/quicktime',
'movie'     => 'video/x-sgi-movie',
'mp2'       => 'audio/mpeg',
'mp3'       => 'audio/mpeg',
'mpe'       => 'video/mpeg',
'mpeg'      => 'video/mpeg',
'mpg'       => 'video/mpeg',
'mpga'      => 'audio/mpeg',
'ms'        => 'application/x-troff-ms',
'msh'       => 'model/mesh',
'mxu'       => 'video/vnd.mpegurl',
'nc'        => 'application/x-netcdf',
'oda'       => 'application/oda',
'odb'       => 'application/vnd.oasis.opendocument.database',
'odc'       => 'application/vnd.oasis.opendocument.chart',
'odf'       => 'application/vnd.oasis.opendocument.formula',
'odg'       => 'application/vnd.oasis.opendocument.graphics',
'odi'       => 'application/vnd.oasis.opendocument.image',
'odm'       => 'application/vnd.oasis.opendocument.text-master',
'odp'       => 'application/vnd.oasis.opendocument.presentation',
'ods'       => 'application/vnd.oasis.opendocument.spreadsheet',
'odt'       => 'application/vnd.oasis.opendocument.text',
'ogg'       => 'application/ogg',
'otg'       => 'application/vnd.oasis.opendocument.graphics-template',
'oth'       => 'application/vnd.oasis.opendocument.text-web',
'otp'       => 'application/vnd.oasis.opendocument.presentation-template',
'ots'       => 'application/vnd.oasis.opendocument.spreadsheet-template',
'ott'       => 'application/vnd.oasis.opendocument.text-template',
'pac'       => 'application/x-ns-proxy-autoconfig',
'pbm'       => 'image/x-portable-bitmap',
'pdb'       => 'chemical/x-pdb',
'pdf'       => 'application/pdf',
'pgm'       => 'image/x-portable-graymap',
'pgn'       => 'application/x-chess-pgn',
'pgp'       => 'application/pgp',
'php'       => 'application/x-httpd-php',
'pht'       => 'application/x-httpd-php',
'phtml'     => 'application/x-httpd-php',
'pl'        => 'application/x-perl',
'pm'        => 'application/x-perl',
'png'       => 'image/png',
'pnm'       => 'image/x-portable-anymap',
'pot'       => 'application/vnd.ms-powerpoint',
'potm'      => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
'potx'      => 'application/vnd.openxmlformats-officedocument.presentationml.template',
'ppa'       => 'application/vnd.ms-powerpoint',
'ppam'      => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
'ppm'       => 'image/x-portable-pixmap',
'pps'       => 'application/vnd.ms-powerpoint',
'ppsm'      => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
'ppsx'      => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
'ppt'       => 'application/vnd.ms-powerpoint',
'pptm'      => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
'pptx'      => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
'ps'        => 'application/postscript',
'qdf'       => 'application/octet-stream',
'qt'        => 'video/quicktime',
'ra'        => 'audio/x-realaudio',
'ram'       => 'audio/x-pn-realaudio',
'ras'       => 'image/x-cmu-raster',
'rgb'       => 'image/x-rgb',
'rm'        => 'audio/x-pn-realaudio',
'roff'      => 'application/x-troff',
'rpm'       => 'application/x-rpm',
'rtf'       => 'text/rtf',
'rtx'       => 'text/richtext',
'sda'       => 'application/vnd.stardivision.draw',
'sdc'       => 'application/vnd.stardivision.calc',
'sdd'       => 'application/vnd.stardivision.impress',
'sdw'       => 'application/vnd.stardivision.writer',
'sgl'       => 'application/vnd.stardivision.writer-global',
'sgm'       => 'text/sgml',
'sgml'      => 'text/sgml',
'sh'        => 'application/x-sh',
'shar'      => 'application/x-shar',
'silo'      => 'model/mesh',
'sis'       => 'application/vnd.symbian.install',
'sit'       => 'application/x-stuffit',
'skd'       => 'application/x-koan',
'skm'       => 'application/x-koan',
'skp'       => 'application/x-koan',
'skt'       => 'application/x-koan',
'smf'       => 'application/vnd.stardivision.math',
'smi'       => 'application/smil',
'smil'      => 'application/smil',
'snd'       => 'audio/basic',
'so'        => 'application/octet-stream',
'spl'       => 'application/x-futuresplash',
'src'       => 'application/x-wais-source',
'stc'       => 'application/vnd.sun.xml.calc.template',
'std'       => 'application/vnd.sun.xml.draw.template',
'sti'       => 'application/vnd.sun.xml.impress.template',
'stw'       => 'application/vnd.sun.xml.writer.template',
'sv4cpio'   => 'application/x-sv4cpio',
'sv4crc'    => 'application/x-sv4crc',
'swf'       => 'application/x-shockwave-flash',
'sxc'       => 'application/vnd.sun.xml.calc',
'sxd'       => 'application/vnd.sun.xml.draw',
'sxg'       => 'application/vnd.sun.xml.writer.global',
'sxi'       => 'application/vnd.sun.xml.impress',
'sxm'       => 'application/vnd.sun.xml.math',
'sxw'       => 'application/vnd.sun.xml.writer',
't'         => 'application/x-troff',
'tar'       => 'application/x-tar',
'tcl'       => 'application/x-tcl',
'tex'       => 'application/x-tex',
'texi'      => 'application/x-texinfo',
'texinfo'   => 'application/x-texinfo',
'tgz'       => 'application/x-gzip',
'tif'       => 'image/tiff',
'tiff'      => 'image/tiff',
'torrent'   => 'application/x-bittorrent',
'tr'        => 'application/x-troff',
'tsv'       => 'text/tab-separated-values',
'txt'       => 'text/plain',
'ustar'     => 'application/x-ustar',
'vcd'       => 'application/x-cdlink',
'vcf'       => 'text/x-vCard',
'vcs'       => 'text/x-vCalendar',
'vrml'      => 'model/vrml',
'wav'       => 'audio/x-wav',
'wax'       => 'audio/x-ms-wax',
'wbmp'      => 'image/vnd.wap.wbmp',
'wbxml'     => 'application/vnd.wap.wbxml',
'wk'        => 'application/x-123',
'wz'        => 'application/x-Wingz',
'wrd'       => 'application/msword',
'wrl'       => 'model/vrml',
'wm'        => 'video/x-ms-wm',
'wma'       => 'audio/x-ms-wma',
'wml'       => 'text/vnd.wap.wml',
'wmlc'      => 'application/vnd.wap.wmlc',
'wmls'      => 'text/vnd.wap.wmlscript',
'wmlsc'     => 'application/vnd.wap.wmlscriptc',
'wmv'       => 'video/x-ms-wmv',
'wmx'       => 'video/x-ms-wmx',
'wp5'       => 'application/wordperfect5.1',
'wrl'       => 'model/vrml',
'wvx'       => 'video/x-ms-wvx',
'xbm'       => 'image/x-xbitmap',
'xht'       => 'application/xhtml+xml',
'xhtml'     => 'application/xhtml+xml',
'xls'       => 'application/vnd.ms-excel',
'xml'       => 'text/xml',
'xpm'       => 'image/x-xpixmap',
'xsl'       => 'text/xml',
'xwd'       => 'image/x-xwindowdump',
'xyz'       => 'chemical/x-xyz',
'z'         => 'application/x-compress',
'Z'         => 'application/x-compress',
'zip'       => 'application/zip',
'default'   =>''
    );