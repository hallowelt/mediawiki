<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace Wikimedia\Mime;

/**
 * Map of MIME types to file extensions and media types.
 *
 * @internal
 * @ingroup Mime
 */
class MimeMap {
	/** @var array Map of MIME types to an array of file extensions */
	public const MIME_EXTENSIONS = [
		'application/ogg' => [ 'ogx', 'ogg', 'ogm', 'ogv', 'oga', 'spx', 'opus' ],
		'application/pdf' => [ 'pdf' ],
		'application/vnd.apple.mpegurl' => [ 'm3u8', 'm3u' ],
		'application/vnd.ms-opentype' => [ 'otf' ],
		'application/vnd.oasis.opendocument.chart' => [ 'odc' ],
		'application/vnd.oasis.opendocument.chart-template' => [ 'otc' ],
		'application/vnd.oasis.opendocument.database' => [ 'odb' ],
		'application/vnd.oasis.opendocument.formula' => [ 'odf' ],
		'application/vnd.oasis.opendocument.formula-template' => [ 'otf' ],
		'application/vnd.oasis.opendocument.graphics' => [ 'odg' ],
		'application/vnd.oasis.opendocument.graphics-template' => [ 'otg' ],
		'application/vnd.oasis.opendocument.image' => [ 'odi' ],
		'application/vnd.oasis.opendocument.image-template' => [ 'oti' ],
		'application/vnd.oasis.opendocument.presentation' => [ 'odp' ],
		'application/vnd.oasis.opendocument.presentation-template' => [ 'otp' ],
		'application/vnd.oasis.opendocument.spreadsheet' => [ 'ods' ],
		'application/vnd.oasis.opendocument.spreadsheet-template' => [ 'ots' ],
		'application/vnd.oasis.opendocument.text' => [ 'odt' ],
		'application/vnd.oasis.opendocument.text-master' => [ 'otm', 'odm' ],
		'application/vnd.oasis.opendocument.text-template' => [ 'ott' ],
		'application/vnd.oasis.opendocument.text-web' => [ 'oth' ],
		'application/javascript' => [ 'js' ],
		'application/x-mpegurl' => [ 'm3u', 'm3u8' ],
		'application/x-shockwave-flash' => [ 'swf' ],
		'audio/midi' => [ 'mid', 'midi', 'kar' ],
		'audio/mpeg' => [ 'mpga', 'mpa', 'mp2', 'mp3' ],
		'audio/x-aiff' => [ 'aif', 'aiff', 'aifc' ],
		'audio/x-wav' => [ 'wav' ],
		'audio/ogg' => [ 'oga', 'spx', 'ogg', 'opus' ],
		'audio/opus' => [ 'opus', 'ogg', 'oga', 'spx' ],
		'image/x-bmp' => [ 'bmp' ],
		'image/gif' => [ 'gif' ],
		'image/jpeg' => [ 'jpeg', 'jpg', 'jpe', 'jps' ],
		'image/png' => [ 'png', 'apng' ],
		'image/svg+xml' => [ 'svg' ],
		'image/svg' => [ 'svg' ],
		'image/tiff' => [ 'tiff', 'tif' ],
		'image/vnd.djvu' => [ 'djvu', 'djv' ],
		'image/x.djvu' => [ 'djvu' ],
		'image/x-djvu' => [ 'djvu' ],
		'image/x-portable-pixmap' => [ 'ppm' ],
		'image/x-xcf' => [ 'xcf' ],
		'text/plain' => [ 'txt' ],
		'text/html' => [ 'html', 'htm' ],
		'video/ogg' => [ 'ogv', 'ogm', 'ogg' ],
		'video/mpeg' => [ 'mpg', 'mpeg', 'mpe' ],
		'application/acad' => [ 'dwg' ],
		'application/andrew-inset' => [ 'ez' ],
		'application/mac-binhex40' => [ 'hqx' ],
		'application/mac-compactpro' => [ 'cpt' ],
		'application/mathml+xml' => [ 'mathml' ],
		'application/msword' => [ 'doc', 'dot' ],
		'application/octet-stream' => [ 'bin', 'dms', 'lha', 'lzh', 'exe', 'class', 'so', 'dll' ],
		'application/oda' => [ 'oda' ],
		'application/postscript' => [ 'ai', 'eps', 'ps' ],
		'application/rdf+xml' => [ 'rdf', 'owl' ],
		'application/smil' => [ 'smi', 'smil' ],
		'application/srgs' => [ 'gram' ],
		'application/srgs+xml' => [ 'grxml' ],
		'application/vnd.mif' => [ 'mif' ],
		'application/vnd.ms-excel' => [ 'xls', 'xlt', 'xla' ],
		'application/vnd.ms-powerpoint' => [ 'ppt', 'pot', 'pps', 'ppa' ],
		'application/vnd.wap.wbxml' => [ 'wbxml' ],
		'application/vnd.wap.wmlc' => [ 'wmlc' ],
		'application/vnd.wap.wmlscriptc' => [ 'wmlsc' ],
		'application/voicexml+xml' => [ 'vxml' ],
		'application/x-7z-compressed' => [ '7z' ],
		'application/x-bcpio' => [ 'bcpio' ],
		'application/x-bzip' => [ 'bz' ],
		'application/x-bzip2' => [ 'bz2' ],
		'application/x-cdlink' => [ 'vcd' ],
		'application/x-chess-pgn' => [ 'pgn' ],
		'application/x-cpio' => [ 'cpio' ],
		'application/x-csh' => [ 'csh' ],
		'application/x-dia-diagram' => [ 'dia' ],
		'application/x-director' => [ 'dcr', 'dir', 'dxr' ],
		'application/x-dvi' => [ 'dvi' ],
		'application/x-futuresplash' => [ 'spl' ],
		'application/x-gtar' => [ 'gtar', 'tar' ],
		'application/x-gzip' => [ 'gz' ],
		'application/x-hdf' => [ 'hdf' ],
		'application/x-jar' => [ 'jar' ],
		'application/json' => [ 'json' ],
		'application/x-koan' => [ 'skp', 'skd', 'skt', 'skm' ],
		'application/x-latex' => [ 'latex' ],
		'application/x-netcdf' => [ 'nc', 'cdf' ],
		'application/x-sh' => [ 'sh' ],
		'application/x-shar' => [ 'shar' ],
		'application/x-stuffit' => [ 'sit' ],
		'application/x-sv4cpio' => [ 'sv4cpio' ],
		'application/x-sv4crc' => [ 'sv4crc' ],
		'application/x-tar' => [ 'tar' ],
		'application/x-tcl' => [ 'tcl' ],
		'application/x-tex' => [ 'tex' ],
		'application/x-texinfo' => [ 'texinfo', 'texi' ],
		'application/x-troff' => [ 't', 'tr', 'roff' ],
		'application/x-troff-man' => [ 'man' ],
		'application/x-troff-me' => [ 'me' ],
		'application/x-troff-ms' => [ 'ms' ],
		'application/x-ustar' => [ 'ustar' ],
		'application/x-wais-source' => [ 'src' ],
		'application/x-xpinstall' => [ 'xpi' ],
		'application/xhtml+xml' => [ 'xhtml', 'xht' ],
		'application/xslt+xml' => [ 'xslt' ],
		'application/xml' => [ 'xml', 'xsl', 'xsd', 'kml' ],
		'application/xml-dtd' => [ 'dtd' ],
		'application/zip' =>
			[ 'zip', 'jar', 'xpi', 'sxc', 'stc', 'sxd', 'std', 'sxi', 'sti', 'sxm', 'stm', 'sxw', 'stw' ],
		'application/x-rar' => [ 'rar' ],
		'font/collection' => [ 'ttc' ],
		'font/otf' => [ 'ttf', 'otf' ],
		'font/sfnt' => [ 'ttf', 'otf' ],
		'font/ttf' => [ 'ttf', 'otf' ],
		'application/font-sfnt' => [ 'ttf' ],
		'font/woff' => [ 'woff' ],
		'application/font-woff' => [ 'woff' ],
		'font/woff2' => [ 'woff2' ],
		'application/font-woff2' => [ 'woff2' ],
		'application/vnd.ms-fontobject' => [ 'eot' ],
		'application/x-font-ttf' => [ 'ttf' ],
		'audio/basic' => [ 'au', 'snd' ],
		'video/webm' => [ 'webm' ],
		'audio/webm' => [ 'webm' ],
		'audio/x-matroska' => [ 'mka', 'mkv' ],
		'audio/x-mpegurl' => [ 'm3u' ],
		'audio/x-ogg' => [ 'oga', 'ogg', 'spx', 'opus' ],
		'audio/x-pn-realaudio' => [ 'ram', 'rm' ],
		'audio/x-pn-realaudio-plugin' => [ 'rpm' ],
		'audio/x-realaudio' => [ 'ra' ],
		'audio/wav' => [ 'wav' ],
		'audio/x-flac' => [ 'flac' ],
		'audio/flac' => [ 'flac' ],
		'chemical/x-pdb' => [ 'pdb' ],
		'chemical/x-xyz' => [ 'xyz' ],
		'image/bmp' => [ 'bmp' ],
		'image/cgm' => [ 'cgm' ],
		'image/ief' => [ 'ief' ],
		'image/jp2' => [ 'jp2', 'j2k', 'jpg2' ],
		'image/jpx' => [ 'jpf', 'jpx' ],
		'image/vnd.microsoft.icon' => [ 'ico' ],
		'image/vnd.wap.wbmp' => [ 'wbmp' ],
		'image/webp' => [ 'webp' ],
		'image/x-cmu-raster' => [ 'ras' ],
		'image/x-icon' => [ 'ico' ],
		'image/x-jps' => [ 'jps' ],
		'image/x-ms-bmp' => [ 'bmp' ],
		'image/x-portable-anymap' => [ 'pnm' ],
		'image/x-portable-bitmap' => [ 'pbm' ],
		'image/x-portable-graymap' => [ 'pgm' ],
		'image/x-rgb' => [ 'rgb' ],
		'image/x-photoshop' => [ 'psd' ],
		'image/x-xbitmap' => [ 'xbm' ],
		'image/x-xpixmap' => [ 'xpm' ],
		'image/x-xwindowdump' => [ 'xwd' ],
		'model/iges' => [ 'igs', 'iges' ],
		'model/mesh' => [ 'msh', 'mesh', 'silo' ],
		'model/vrml' => [ 'wrl', 'vrml' ],
		'text/calendar' => [ 'ics', 'ifb' ],
		'text/css' => [ 'css' ],
		'text/csv' => [ 'csv' ],
		'text/richtext' => [ 'rtx' ],
		'text/rtf' => [ 'rtf' ],
		'text/sgml' => [ 'sgml', 'sgm' ],
		'text/tab-separated-values' => [ 'tsv' ],
		'text/vnd.wap.wml' => [ 'wml' ],
		'text/vnd.wap.wmlscript' => [ 'wmls' ],
		'text/xml' => [ 'xml', 'xsl', 'xslt', 'rss', 'rdf' ],
		'text/x-component' => [ 'htc' ],
		'text/x-less' => [ 'less' ], // T399672
		'text/x-setext' => [ 'etx' ],
		'text/x-sawfish' => [ 'jl' ],
		'video/mp4' => [ 'mp4', 'm4a', 'm4p', 'm4b', 'm4r', 'm4v' ],
		'audio/mp4' => [ 'm4a' ],
		'video/quicktime' => [ 'qt', 'mov' ],
		'video/vnd.mpegurl' => [ 'mxu' ],
		'video/x-flv' => [ 'flv' ],
		'video/x-matroska' => [ 'mkv', 'mka' ],
		'video/x-msvideo' => [ 'avi' ],
		'video/x-ogg' => [ 'ogv', 'ogm', 'ogg' ],
		'video/x-sgi-movie' => [ 'movie' ],
		'x-conference/x-cooltalk' => [ 'ice' ],
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => [ 'docx' ],
		'application/vnd.openxmlformats-officedocument.wordprocessingml.template' => [ 'dotx' ],
		'application/vnd.ms-word.document.macroenabled.12' => [ 'docm' ],
		'application/vnd.ms-word.template.macroenabled.12' => [ 'dotm' ],
		'application/vnd.openxmlformats-officedocument.presentationml.template' => [ 'potx' ],
		'application/vnd.openxmlformats-officedocument.presentationml.slideshow' => [ 'ppsx' ],
		'application/vnd.openxmlformats-officedocument.presentationml.presentation' => [ 'pptx' ],
		'application/vnd.ms-powerpoint.addin.macroenabled.12' => [ 'ppam' ],
		'application/vnd.ms-powerpoint.presentation.macroenabled.12' => [ 'pptm', 'potm' ],
		'application/vnd.ms-powerpoint.slideshow.macroenabled.12' => [ 'ppsm' ],
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => [ 'xlsx' ],
		'application/vnd.openxmlformats-officedocument.spreadsheetml.template' => [ 'xltx' ],
		'application/vnd.ms-excel.sheet.macroenabled.12' => [ 'xlsm' ],
		'application/vnd.ms-excel.template.macroenabled.12' => [ 'xltm' ],
		'application/vnd.ms-excel.addin.macroenabled.12' => [ 'xlam' ],
		'application/vnd.ms-excel.sheet.binary.macroenabled.12' => [ 'xlsb' ],
		'model/gltf-binary' => [ 'glb' ],
		'model/gltf+json' => [ 'gltf' ],
		'model/vnd.dwfx+xps' => [ 'dwfx' ],
		'application/vnd.ms-xpsdocument' => [ 'xps' ],
		'chemical/x-mdl-molfile' => [ 'mol' ],
		'chemical/x-mdl-sdfile' => [ 'sdf' ],
		'chemical/x-mdl-rxnfile' => [ 'rxn' ],
		'chemical/x-mdl-rdfile' => [ 'rd' ],
		'chemical/x-mdl-rgfile' => [ 'rg' ],
		'application/x-amf' => [ 'amf' ],
		'application/sla' => [ 'stl' ],
		'application/wasm' => [ 'wasm' ],

		// Vague pseudo-types should be at the end so that they don't take
		// precedence over the more specific types above in getMimeTypesFromExtension()
		'application/x-opc+zip' => [
			'docx', 'dotx', 'docm', 'dotm', 'potx', 'ppsx', 'pptx', 'ppam', 'pptm', 'potm', 'ppsm',
			'xlsx', 'xltx', 'xlsm', 'xltm', 'xlam', 'xlsb', 'dwfx', 'xps'
		],
		'application/vnd.oasis.opendocument' => [
			'odt', 'ott', 'odg', 'otg', 'odp', 'otp', 'ods', 'ots', 'odc', 'otc',
			'odi', 'oti', 'odf', 'otf', 'odm', 'oth',
		]
	];

	/** @var array Map of built-in media types and their associated MIME types */
	public const MEDIA_TYPES = [
		MEDIATYPE_OFFICE => [
			'application/pdf',
			'application/vnd.oasis.opendocument.chart',
			'application/vnd.oasis.opendocument.chart-template',
			'application/vnd.oasis.opendocument.database',
			'application/vnd.oasis.opendocument.formula',
			'application/vnd.oasis.opendocument.formula-template',
			'application/vnd.oasis.opendocument.graphics',
			'application/vnd.oasis.opendocument.graphics-template',
			'application/vnd.oasis.opendocument.image',
			'application/vnd.oasis.opendocument.image-template',
			'application/vnd.oasis.opendocument.presentation',
			'application/vnd.oasis.opendocument.presentation-template',
			'application/vnd.oasis.opendocument.spreadsheet',
			'application/vnd.oasis.opendocument.spreadsheet-template',
			'application/vnd.oasis.opendocument.text',
			'application/vnd.oasis.opendocument.text-template',
			'application/vnd.oasis.opendocument.text-master',
			'application/vnd.oasis.opendocument.text-web',
			'application/pdf',
			'application/acrobat',
			'application/msword',
			'application/vnd.ms-excel',
			'application/vnd.ms-powerpoint',
			'application/x-director',
			'image/vnd.djvu',
			'image/x.djvu',
			'image/x-djvu',
			'text/rtf',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
			'application/vnd.ms-word.document.macroenabled.12',
			'application/vnd.ms-word.template.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.presentationml.template',
			'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
			'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'application/vnd.ms-powerpoint.addin.macroenabled.12',
			'application/vnd.ms-powerpoint.presentation.macroenabled.12',
			'application/vnd.ms-powerpoint.presentation.macroenabled.12',
			'application/vnd.ms-powerpoint.slideshow.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
			'application/vnd.ms-excel.sheet.macroenabled.12',
			'application/vnd.ms-excel.template.macroenabled.12',
			'application/vnd.ms-excel.addin.macroenabled.12',
			'application/vnd.ms-excel.sheet.binary.macroenabled.12',
		],
		MEDIATYPE_EXECUTABLE => [
			'application/javascript',
			'text/javascript',
			'application/x-javascript',
			'application/javascript',
			'text/javascript',
			'application/x-javascript',
			'application/x-ecmascript',
			'text/ecmascript',
			'application/x-bash',
			'application/x-sh',
			'application/x-csh',
			'application/x-tcsh',
			'application/x-tcl',
			'application/x-perl',
			'application/x-python',
			'application/wasm',
		],
		MEDIATYPE_MULTIMEDIA => [
			'application/x-shockwave-flash',
			'application/ogg',
			'application/vnd.apple.mpegurl',
			'application/x-mpegurl',
			'audio/ogg',
			'video/ogg',
			'application/ogg',
			'application/x-ogg',
			'audio/ogg',
			'audio/x-ogg',
			'video/ogg',
			'video/x-ogg',
			'application/x-shockwave-flash',
			'audio/x-pn-realaudio-plugin',
			'model/iges',
			'model/mesh',
			'model/vrml',
			'video/quicktime',
			'video/x-msvideo',
		],
		MEDIATYPE_AUDIO => [
			'audio/midi',
			'audio/x-aiff',
			'audio/x-wav',
			'audio/mp3',
			'audio/mpeg',
			'audio/mpeg',
			'audio/mp3',
			'audio/mpeg3',
			'audio/mp4',
			'audio/wav',
			'audio/x-wav',
			'audio/wave',
			'audio/midi',
			'audio/mid',
			'audio/basic',
			'audio/ogg',
			'audio/opus',
			'audio/x-aiff',
			'audio/x-pn-realaudio',
			'audio/x-realaudio',
			'audio/webm',
			'audio/x-matroska',
			'audio/x-flac',
			'audio/flac',
		],
		MEDIATYPE_BITMAP => [
			'image/x-bmp',
			'image/x-ms-bmp',
			'image/bmp',
			'image/gif',
			'image/jpeg',
			'image/png',
			'image/tiff',
			'image/vnd.djvu',
			'image/x-xcf',
			'image/x-portable-pixmap',
			'image/gif',
			'image/png',
			'image/x-png',
			'image/ief',
			'image/jpeg',
			'image/x-jps',
			'image/pjpeg',
			'image/jp2',
			'image/jpx',
			'image/xbm',
			'image/tiff',
			'image/x-icon',
			'image/x-ico',
			'image/vnd.microsoft.icon',
			'image/x-rgb',
			'image/x-portable-pixmap',
			'image/x-portable-graymap',
			'image/x-portable-greymap',
			'image/x-bmp',
			'image/x-ms-bmp',
			'image/bmp',
			'application/x-bmp',
			'application/bmp',
			'image/x-photoshop',
			'image/psd',
			'image/x-psd',
			'image/photoshop',
			'image/vnd.adobe.photoshop',
			'image/webp',
		],
		MEDIATYPE_DRAWING => [
			'image/svg+xml',
			'image/svg+xml',
			'application/svg+xml',
			'application/svg',
			'image/svg',
			'application/postscript',
			'application/x-latex',
			'application/x-tex',
			'application/x-dia-diagram',
			'application/acad',
			'application/x-acad',
			'application/autocad_dwg',
			'image/x-dwg',
			'application/dwg',
			'application/x-dwg',
			'application/x-autocad',
			'image/vnd.dwg',
			'drawing/dwg',
			'chemical/x-mdl-molfile',
			'chemical/x-mdl-sdfile',
			'chemical/x-mdl-rxnfile',
			'chemical/x-mdl-rdfile',
			'chemical/x-mdl-rgfile',
		],
		MEDIATYPE_TEXT => [
			'text/plain',
			'text/html',
			'text/plain',
			'text/html',
			'application/xhtml+xml',
			'application/xml',
			'text/xml',
			'text',
			'application/json',
			'text/csv',
			'text/tab-separated-values',
		],
		MEDIATYPE_VIDEO => [
			'video/ogg',
			'video/mpeg',
			'video/mpeg',
			'application/mpeg',
			'video/ogg',
			'video/x-sgi-video',
			'video/x-flv',
			'video/webm',
			'video/x-matroska',
			'video/mp4',
		],
		MEDIATYPE_UNKNOWN => [
			'unknown/unknown',
			'application/octet-stream',
			'application/x-empty',
		],
		MEDIATYPE_ARCHIVE => [
			'application/zip',
			'application/x-zip',
			'application/x-gzip',
			'application/x-bzip',
			'application/x-bzip2',
			'application/x-tar',
			'application/x-stuffit',
			'application/x-opc+zip',
			'application/x-7z-compressed',
		],
		MEDIATYPE_3D => [
			'application/sla',
			'model/gltf-binary',
			'model/gltf+json',
		],
	];

	/** @var array Map of variant MIME types to their canonical MIME type */
	public const MIME_TYPE_ALIASES = [
		'text/javascript' => 'application/javascript',
		'application/x-javascript' => 'application/javascript',
		'audio/mpeg' => 'audio/mp3',
		'audio/ogg' => 'application/ogg',
		'video/ogg' => 'application/ogg',
		'image/x-ms-bmp' => 'image/x-bmp',
		'image/bmp' => 'image/x-bmp',
		'application/octet-stream' => 'unknown/unknown',
		'application/x-empty' => 'unknown/unknown',
		'image/x-png' => 'image/png',
		'image/pjpeg' => 'image/jpeg',
		'image/x-ico' => 'image/x-icon',
		'image/vnd.microsoft.icon' => 'image/x-icon',
		'image/x-portable-greymap' => 'image/x-portable-graymap',
		'application/x-bmp' => 'image/x-bmp',
		'application/bmp' => 'image/x-bmp',
		'image/psd' => 'image/x-photoshop',
		'image/x-psd' => 'image/x-photoshop',
		'image/photoshop' => 'image/x-photoshop',
		'image/vnd.adobe.photoshop' => 'image/x-photoshop',
		'application/svg+xml' => 'image/svg+xml',
		'application/svg' => 'image/svg+xml',
		'image/svg' => 'image/svg+xml',
		'audio/mp3' => 'audio/mpeg',
		'audio/mpeg3' => 'audio/mpeg',
		'audio/x-wav' => 'audio/wav',
		'audio/wave' => 'audio/wav',
		'audio/mid' => 'audio/midi',
		'application/mpeg' => 'video/mpeg',
		'application/x-ogg' => 'application/ogg',
		'audio/x-ogg' => 'application/ogg',
		'video/x-ogg' => 'application/ogg',
		'application/xhtml+xml' => 'text/html',
		'text/xml' => 'application/xml',
		'application/x-zip' => 'application/zip',
		'application/x-ecmascript' => 'application/javascript',
		'text/ecmascript' => 'application/javascript',
		'application/acrobat' => 'application/pdf',
		'image/x.djvu' => 'image/vnd.djvu',
		'image/x-djvu' => 'image/vnd.djvu',
		'application/x-acad' => 'application/acad',
		'application/autocad_dwg' => 'application/acad',
		'image/x-dwg' => 'application/acad',
		'application/dwg' => 'application/acad',
		'application/x-dwg' => 'application/acad',
		'application/x-autocad' => 'application/acad',
		'image/vnd.dwg' => 'application/acad',
		'drawing/dwg' => 'application/acad',
		'image/jpeg2000' => 'image/jp2',
		'image/jpeg2000-image' => 'image/jp2',
		'image/x-jpeg2000-image' => 'image/jp2',
		'application/csv' => 'text/csv',
		'application/x-csv' => 'text/csv',
		'text/x-csv' => 'text/csv',
		'text/comma-separated-values' => 'text/csv',
		'text/x-comma-separated-values' => 'text/csv',
	];
}
