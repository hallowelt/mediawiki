#!/usr/bin/env python
# -*- coding: utf-8 -*-
# @author Philip
import os
import platform
import re
import shutil
import sys
import tarfile
import zipfile

pyversion = platform.python_version()
islinux = platform.system().lower() == 'linux'

if pyversion[:3] in ['2.6', '2.7']:
    import urllib as urllib_request
    import codecs
    open = codecs.open
    _unichr = unichr
    if sys.maxunicode < 0x10000:
        def unichr(i):
            if i < 0x10000:
                return _unichr(i)
            else:
                return _unichr(0xD7C0 + (i >> 10)) + _unichr(0xDC00 + (i & 0x3FF))
elif pyversion[:2] == '3.':
    import urllib.request as urllib_request
    unichr = chr


def unichr2(*args):
    return [unichr(int(i.split('<')[0][2:], 16)) for i in args]


def unichr3(*args):
    return [unichr(int(i[2:7], 16)) for i in args if i[2:7]]

# DEFINE
UNIHAN_VER = '12.1.0'
SF_MIRROR = 'master'
SCIM_TABLES_VER = '0.5.14.1'
SCIM_PINYIN_VER = '0.5.92'
LIBTABE_VER = '0.2.3'
# END OF DEFINE


def download(url, dest):
    if os.path.isfile(dest):
        print('File %s is up to date.' % dest)
        return
    global islinux
    if islinux:
        # we use wget instead urlretrieve under Linux,
        # because wget could display details like download progress
        os.system('wget %s -O %s' % (url, dest))
    else:
        print('Downloading from [%s] ...' % url)
        urllib_request.urlretrieve(url, dest)
        print('Download complete.\n')
    return


def uncompress(fp, member, encoding='U8'):
    name = member.rsplit('/', 1)[-1]
    print('Extracting %s ...' % name)
    fp.extract(member)
    shutil.move(member, name)
    if '/' in member:
        shutil.rmtree(member.split('/', 1)[0])
    if pyversion[:1] in ['2']:
        fc = open(name, 'rb', encoding, 'ignore')
    else:
        fc = open(name, 'r', encoding=encoding, errors='ignore')
    return fc

unzip = lambda path, member, encoding = 'U8': \
        uncompress(zipfile.ZipFile(path), member, encoding)

untargz = lambda path, member, encoding = 'U8': \
        uncompress(tarfile.open(path, 'r:gz'), member, encoding)


def parserCore(fp, pos, beginmark=None, endmark=None):
    if beginmark and endmark:
        start = False
    else:
        start = True
    mlist = set()
    for line in fp:
        if beginmark and line.startswith(beginmark):
            start = True
            continue
        elif endmark and line.startswith(endmark):
            break
        if start and not line.startswith('#'):
            elems = line.split()
            if len(elems) < 2:
                continue
            elif len(elems[0]) > 1 and len(elems[pos]) > 1:  # words only
                mlist.add(elems[pos])
    return mlist


def tablesParser(path, name):
    """ Read file from scim-tables and parse it. """
    global SCIM_TABLES_VER
    src = 'scim-tables-%s/tables/zh/%s' % (SCIM_TABLES_VER, name)
    fp = untargz(path, src, 'U8')
    return parserCore(fp, 1, 'BEGIN_TABLE', 'END_TABLE')

ezbigParser = lambda path: tablesParser(path, 'EZ-Big.txt.in')
wubiParser = lambda path: tablesParser(path, 'Wubi.txt.in')
zrmParser = lambda path: tablesParser(path, 'Ziranma.txt.in')


def phraseParser(path):
    """ Read phrase_lib.txt and parse it. """
    global SCIM_PINYIN_VER
    src = 'scim-pinyin-%s/data/phrase_lib.txt' % SCIM_PINYIN_VER
    fp = untargz(path, src, 'U8')
    return parserCore(fp, 0)


def tsiParser(path):
    """ Read tsi.src and parse it. """
    src = 'libtabe/tsi-src/tsi.src'
    fp = untargz(path, src, 'big5hkscs')
    return parserCore(fp, 0)


def unihanParser(path):
    """ Read Unihan_Variants.txt and parse it. """
    fp = unzip(path, 'Unihan_Variants.txt', 'U8')
    t2s = dict()
    s2t = dict()
    for line in fp:
        if line.startswith('#'):
            continue
        else:
            elems = line.split()
            if len(elems) < 3:
                continue
            type = elems.pop(1)
            elems = unichr2(*elems)
            if type == 'kTraditionalVariant':
                s2t[elems[0]] = elems[1:]
            elif type == 'kSimplifiedVariant':
                t2s[elems[0]] = elems[1:]
    fp.close()
    return (t2s, s2t)


def applyExcludes(mlist, path):
    """ Apply exclude rules from path to mlist. """
    if pyversion[:1] in ['2']:
        excludes = open(path, 'rb', 'U8').read().split()
    else:
        excludes = open(path, 'r', encoding='U8').read().split()
    excludes = [word.split('#')[0].strip() for word in excludes]
    excludes = '|'.join(excludes)
    excptn = re.compile('.*(?:%s).*' % excludes)
    diff = [mword for mword in mlist if excptn.search(mword)]
    mlist.difference_update(diff)
    return mlist


def charManualTable(path):
    fp = open(path, 'r', encoding='U8')
    for line in fp:
        elems = line.split('#')[0].split('|')
        elems = unichr3(*elems)
        if len(elems) > 1:
            yield elems[0], elems[1:]


def toManyRules(src_table):
    tomany = set()
    if pyversion[:1] in ['2']:
        for (f, t) in src_table.iteritems():
            for i in range(1, len(t)):
                tomany.add(t[i])
    else:
        for (f, t) in src_table.items():
            for i in range(1, len(t)):
                tomany.add(t[i])
    return tomany


def removeRules(path, table):
    fp = open(path, 'r', encoding='U8')
    texc = list()
    for line in fp:
        elems = line.split('=>')
        f = t = elems[0].strip()
        if len(elems) == 2:
            t = elems[1].strip()
        f = f.strip('"').strip("'")
        t = t.strip('"').strip("'")
        if f:
            try:
                table.pop(f)
            except:
                pass
        if t:
            texc.append(t)
    texcptn = re.compile('^(?:%s)$' % '|'.join(texc))
    if pyversion[:1] in ['2']:
        for (tmp_f, tmp_t) in table.copy().iteritems():
            if texcptn.match(tmp_t):
                table.pop(tmp_f)
    else:
        for (tmp_f, tmp_t) in table.copy().items():
            if texcptn.match(tmp_t):
                table.pop(tmp_f)
    return table


def customRules(path):
    fp = open(path, 'r', encoding='U8')
    ret = dict()
    for line in fp:
        line = line.rstrip('\r\n')
        if '#' in line:
            line = line.split('#')[0].rstrip()
        elems = line.split('\t')
        if len(elems) > 1:
            ret[elems[0]] = elems[1]
    return ret


def dictToSortedList(src_table, pos):
    return sorted(src_table.items(), key=lambda m: (m[pos], m[1 - pos]))


def translate(text, conv_table):
    i = 0
    while i < len(text):
        for j in range(len(text) - i, 0, -1):
            f = text[i:][:j]
            t = conv_table.get(f)
            if t:
                text = text[:i] + t + text[i:][j:]
                i += len(t) - 1
                break
        i += 1
    return text


def manualWordsTable(path, conv_table, reconv_table):
    fp = open(path, 'r', encoding='U8')
    reconv_table = reconv_table.copy()
    out_table = {}
    wordlist = [line.split('#')[0].strip() for line in fp]
    wordlist = list(set(wordlist))
    wordlist.sort(key=lambda w: (len(w), w), reverse=True)
    while wordlist:
        word = wordlist.pop()
        new_word = translate(word, conv_table)
        rcv_word = translate(word, reconv_table)
        if word != rcv_word:
            reconv_table[word] = out_table[word] = word
        reconv_table[new_word] = out_table[new_word] = word
    return out_table


def defaultWordsTable(src_wordlist, src_tomany, char_conv_table,
                      char_reconv_table):
    wordlist = list(src_wordlist)
    wordlist.sort(key=lambda w: (len(w), w), reverse=True)
    word_conv_table = {}
    word_reconv_table = {}
    conv_table = char_conv_table.copy()
    reconv_table = char_reconv_table.copy()
    tomanyptn = re.compile('(?:%s)' % '|'.join(src_tomany))
    while wordlist:
        conv_table.update(word_conv_table)
        reconv_table.update(word_reconv_table)
        word = wordlist.pop()
        new_word_len = word_len = len(word)
        while new_word_len == word_len:
            test_word = translate(word, reconv_table)
            new_word = translate(word, conv_table)
            if not reconv_table.get(new_word) and \
               (test_word != word or
                (tomanyptn.search(word) and
                 word != translate(new_word, reconv_table))):
                word_conv_table[word] = new_word
                word_reconv_table[new_word] = word
            try:
                word = wordlist.pop()
            except IndexError:
                break
            new_word_len = len(word)
    return word_reconv_table


def PHPArray(table):
    lines = ['\t\t\'%s\' => \'%s\',' % (f, t) for (f, t) in table if f and t]
    return '\n'.join(lines)


def main():
    # Get Unihan.zip:
    url = 'https://www.unicode.org/Public/%s/ucd/Unihan.zip' % UNIHAN_VER
    han_dest = 'Unihan-%s.zip' % UNIHAN_VER
    download(url, han_dest)

    sfurlbase = 'https://%s.dl.sourceforge.net/sourceforge/' % SF_MIRROR

    # Get scim-tables-$(SCIM_TABLES_VER).tar.gz:
    url = sfurlbase + 'scim/scim-tables-%s.tar.gz' % SCIM_TABLES_VER
    tbe_dest = 'scim-tables-%s.tar.gz' % SCIM_TABLES_VER
    download(url, tbe_dest)

    # Get scim-pinyin-$(SCIM_PINYIN_VER).tar.gz:
    url = sfurlbase + 'scim/scim-pinyin-%s.tar.gz' % SCIM_PINYIN_VER
    pyn_dest = 'scim-pinyin-%s.tar.gz' % SCIM_PINYIN_VER
    download(url, pyn_dest)

    # Get libtabe-$(LIBTABE_VER).tgz:
    url = sfurlbase + 'libtabe/libtabe-%s.tgz' % LIBTABE_VER
    lbt_dest = 'libtabe-%s.tgz' % LIBTABE_VER
    download(url, lbt_dest)

    # Unihan.txt
    (t2s_1tomany, s2t_1tomany) = unihanParser(han_dest)

    t2s_1tomany.update(charManualTable('symme_supp.manual'))
    t2s_1tomany.update(charManualTable('trad2simp.manual'))
    s2t_1tomany.update((t[0], [f]) for (f, t) in charManualTable('symme_supp.manual'))
    s2t_1tomany.update(charManualTable('simp2trad.manual'))

    if pyversion[:1] in ['2']:
        t2s_1to1 = dict([(f, t[0]) for (f, t) in t2s_1tomany.iteritems()])
        s2t_1to1 = dict([(f, t[0]) for (f, t) in s2t_1tomany.iteritems()])
    else:
        t2s_1to1 = dict([(f, t[0]) for (f, t) in t2s_1tomany.items()])
        s2t_1to1 = dict([(f, t[0]) for (f, t) in s2t_1tomany.items()])

    s_tomany = toManyRules(t2s_1tomany)
    t_tomany = toManyRules(s2t_1tomany)

    # noconvert rules
    t2s_1to1 = removeRules('trad2simp_noconvert.manual', t2s_1to1)
    s2t_1to1 = removeRules('simp2trad_noconvert.manual', s2t_1to1)

    # the supper set for word to word conversion
    t2s_1to1_supp = t2s_1to1.copy()
    s2t_1to1_supp = s2t_1to1.copy()
    t2s_1to1_supp.update(customRules('trad2simp_supp_set.manual'))
    s2t_1to1_supp.update(customRules('simp2trad_supp_set.manual'))

    # word to word manual rules
    t2s_word2word_manual = manualWordsTable('simpphrases.manual',
                                            s2t_1to1_supp, t2s_1to1_supp)
    t2s_word2word_manual.update(customRules('toSimp.manual'))
    s2t_word2word_manual = manualWordsTable('tradphrases.manual',
                                            t2s_1to1_supp, s2t_1to1_supp)
    s2t_word2word_manual.update(customRules('toTrad.manual'))

    # word to word rules from input methods
    t_wordlist = set()
    s_wordlist = set()
    t_wordlist.update(ezbigParser(tbe_dest),
                      tsiParser(lbt_dest))
    s_wordlist.update(wubiParser(tbe_dest),
                      zrmParser(tbe_dest),
                      phraseParser(pyn_dest))

    # exclude
    s_wordlist = applyExcludes(s_wordlist, 'simpphrases_exclude.manual')
    t_wordlist = applyExcludes(t_wordlist, 'tradphrases_exclude.manual')

    s2t_supp = s2t_1to1_supp.copy()
    s2t_supp.update(s2t_word2word_manual)
    t2s_supp = t2s_1to1_supp.copy()
    t2s_supp.update(t2s_word2word_manual)

    # parse list to dict
    t2s_word2word = defaultWordsTable(s_wordlist, s_tomany,
                                      s2t_1to1_supp, t2s_supp)
    t2s_word2word.update(t2s_word2word_manual)
    s2t_word2word = defaultWordsTable(t_wordlist, t_tomany,
                                      t2s_1to1_supp, s2t_supp)
    s2t_word2word.update(s2t_word2word_manual)

    # Final tables
    # sorted list toHans
    if pyversion[:1] in ['2']:
        t2s_1to1 = dict([(f, t) for (f, t) in t2s_1to1.iteritems() if f != t])
    else:
        t2s_1to1 = dict([(f, t) for (f, t) in t2s_1to1.items() if f != t])
    toHans = dictToSortedList(t2s_1to1, 0) + dictToSortedList(t2s_word2word, 1)
    # sorted list toHant
    if pyversion[:1] in ['2']:
        s2t_1to1 = dict([(f, t) for (f, t) in s2t_1to1.iteritems() if f != t])
    else:
        s2t_1to1 = dict([(f, t) for (f, t) in s2t_1to1.items() if f != t])
    toHant = dictToSortedList(s2t_1to1, 0) + dictToSortedList(s2t_word2word, 1)
    # sorted list toCN
    toCN = dictToSortedList(customRules('toCN.manual'), 1)
    # sorted list toHK
    toHK = dictToSortedList(customRules('toHK.manual'), 1)
    # sorted list toTW
    toTW = dictToSortedList(customRules('toTW.manual'), 1)

    # Get PHP Array
    php = '''<?php
/**
 * Simplified / Traditional Chinese conversion tables
 *
 * Automatically generated using code and data in maintenance/language/zhtable/
 * Do not modify directly!
 *
 * @file
 */

namespace MediaWiki\Languages\Data;

class ZhConversion {
	public const ZH_TO_HANT = [\n'''
    php += PHPArray(toHant) \
        + '\n\t];\n\n\tpublic const ZH_TO_HANS = [\n' \
        + PHPArray(toHans) \
        + '\n\t];\n\n\tpublic const ZH_TO_TW = [\n' \
        + PHPArray(toTW) \
        + '\n\t];\n\n\tpublic const ZH_TO_HK = [\n' \
        + PHPArray(toHK) \
        + '\n\t];\n\n\tpublic const ZH_TO_CN = [\n' \
        + PHPArray(toCN) \
        + '\n\t];\n}\n'

    if pyversion[:1] in ['2']:
        f = open(os.path.join('..', '..', '..', 'includes', 'languages', 'data', 'ZhConversion.php'), 'wb', encoding='utf8')
    else:
        f = open(os.path.join('..', '..', '..', 'includes', 'languages', 'data', 'ZhConversion.php'), 'w', buffering=4096, encoding='utf8')
    print ('Writing ZhConversion.php ... ')
    f.write(php)
    f.close()

    # Remove temporary files
    print ('Deleting temporary files ... ')
    os.remove('EZ-Big.txt.in')
    os.remove('phrase_lib.txt')
    os.remove('tsi.src')
    os.remove('Unihan_Variants.txt')
    os.remove('Wubi.txt.in')
    os.remove('Ziranma.txt.in')


if __name__ == '__main__':
    main()
